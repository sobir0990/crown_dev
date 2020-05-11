<?php


namespace common\modules\product\forms;


use common\models\Balans;
use common\models\Models;
use common\models\Order;
use common\models\Product;
use common\models\User;
use Yii;
use yii\base\Model;

class CreateOrderForms extends Model
{
    public $store_id;
    public $outgo_data;
    public $models;
    public $user_id;
    public $status;
    public $total_price;

    public function rules()
    {
        return [
            [['store_id', 'outgo_data', 'status', 'user_id', 'total_price'], 'integer'],
            [['models'], 'safe'],
        ];
    }

    /**
     * @return array|bool|Order
     * @throws \yii\web\NotFoundHttpException
     */
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
            $order->status = $this->status;
            $order->price = $this->total_price;
            $order->store_id = $this->store_id;
            $order->outgo_data = $this->outgo_data;
            $order->created_user_id = $userToken->id;
            $order->save();
            $this->balance($this->total_price, $order);
            foreach ($this->models as $item) {
                $model = Models::findOne(['id' => $item['model_id']]);
                if (!is_object($model)) {
                    throw new \DomainException('Not found model ' . $item['model_id']);
                }
                if ($model->getCount($this->store_id) < $item['count']) {
                    $this->addErrors([
                        'modelName' => $model->name,
                        'count' => $model->getCount($this->store_id)
                    ]);
                    return false;
                }
                $this->coming($model, $item, $order->id, $order);
                $this->out($model, $item, $order->id, $order);
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw new \DomainException($exception->getMessage());
        }
        return $order;
    }

    private function coming($model, $item, $order_id, $order)
    {
        $product = new Product();
        $product->price = $model->price;
        $product->models_category_id = $model->category_id;
        $product->models_id = $model->id;
        $product->user_id = $this->user_id;
        $product->status = $order->status;
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

    private function out($model, $item, $order_id, $order)
    {
        $store = new Product();
        $store->user_id = $this->store_id;
        $store->coming_outgo = Product::OUTGO;
        $store->models_id = $model->id;
        $store->coming_data = $this->outgo_data;
        $store->status = $order->status;
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

        switch ($order->status) {
            case Order::STATUS_ARMORED;
                $balance->status = Balans::STATUS_BRON;
                break;
            case Order::STATUS_IMPLOMENTET;
                $balance->status = Balans::STATUS_APPROVED;
                break;
            default;
                $balance->status = Balans::STATUS_NOT_APPROVED;

        }
        if ($balance->save()) {
            return $balance;
        }
        $this->addError(json_decode($balance->errors));
        return false;
    }

}
