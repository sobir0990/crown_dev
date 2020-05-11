<?php


namespace common\modules\product\forms;


use common\models\Balans;
use common\models\Models;
use common\models\Order;
use common\models\Product;
use common\models\User;
use Yii;

class CreateImplementationForms extends Models
{
    public $store_id;
    public $outgo_data;
    public $models;
    public $user_id;
    public $total_price;
    public $created_user_id;

    public function rules()
    {
        return [
            [['store_id', 'outgo_data', 'user_id', 'total_price', 'created_user_id'], 'integer'],
            [['models'], 'safe'],
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        $userToken = User::getByToken();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $order = new Order();
            $order->user_id = $this->user_id;
            $order->status = Order::STATUS_PROCESSING;
            $order->price = $this->total_price;
            $order->store_id = $this->store_id;
            $order->outgo_data = $this->outgo_data;
            $order->created_user_id = $userToken->id;
            $order->save();
            $this->balance($this->total_price, $order);

            if (!($this->models)) {
                throw new \DomainException('Is Empty Model');
            }

            foreach ($this->models as $item) {
                $model = Models::findOne(['id' => $item['model_id']]);
                $count = $model->getCount($this->store_id);
                if (!($count >= $item['count'] && $count !== 0)) {
                    Yii::$app->response->statusCode = 422;
                    $this->addErrors([
                        'model' => $model->name,
                        'count' => $model->getCount($this->store_id)
                    ]);
                    return false;
                }

                $this->coming($model, $item, $order->id);
                $this->out($model, $item, $order->id);
            }

            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw new \DomainException($exception->getMessage());
        }
        return $order;
    }

    private function coming($model, $item, $order_id)
    {
        $product = new Product();
        $product->price = $model->price;
        $product->models_category_id = $model->category_id;
        $product->models_id = $model->id;
        $product->user_id = $this->user_id;
        $product->coming_data = $this->outgo_data;
        $product->coming_outgo = Product::COMING;
        $product->from_user_id = $this->store_id;
        $product->count = $item['count'];
        $product->order_id = $order_id;
        if ($product->save()) {
            return $product;
        }
        $this->addError(json_decode($product->errors));
        return false;
    }

    private function out($model, $item, $order_id)
    {
        $store = new Product();
        $store->user_id = $this->store_id;
        $store->coming_outgo = Product::OUTGO;
        $store->models_id = $model->id;
        $store->coming_data = $this->outgo_data;
        $store->models_category_id = $model->category_id;
        $store->from_user_id = $this->user_id;
        $store->count = $item['count'];
        $store->price = $model->price;
        $store->order_id = $order_id;
        if ($store->save()) {
            return $store;
        }
        $this->addError(json_decode($store->errors));
        return false;
    }

    private function balance($price, $order)
    {
        $balance = new Balans();
        $balance->user_id = $order->user_id;
        $balance->order_id = $order->id;
        $balance->income_outgo = Product::OUTGO;
        $balance->amount = $price;
        $balance->status = Balans::STATUS_NOT_APPROVED;
        if ($balance->save()) {
            return $balance;
        }
        $this->addError(json_decode($balance->errors));
        return false;
    }
}
