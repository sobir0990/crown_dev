<?php


namespace common\modules\product\forms;


use common\models\Balans;
use common\models\Models;
use common\models\Order;
use common\models\OrderClient;
use common\models\Product;
use common\models\Settings;
use common\models\User;
use common\models\UserTokens;
use Yii;
use yii\base\Model;

class CreateOrderClientForms extends Model
{

    public $store_id;
    public $outgo_data;
    public $models;
    public $user_id;
    public $status;
    public $total_price;
    public $price;
    public $phone;
    public $ball = null;
    public $models_ball;

    public function rules()
    {
        return [
            [['store_id', 'outgo_data', 'price', 'status', 'user_id', 'total_price'], 'integer'],
            [['models', 'phone', 'ball', 'models_ball'], 'safe'],
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $user = User::find()->andWhere(['phone' => $this->phone])->one();

            if (!is_object($user)) {
                $user = new User();
                $user->name = $this->phone;
                $user->phone = $this->phone;
                $user->username = $this->phone;
                $user->parent_id = $this->store_id;

                if (!$user->save(false)) {
                    throw new \DomainException('User no created');
                }
                $this->_createRole($user);
                $token = new UserTokens();
                $token->user_id = $user->id;
                $token->expires = time() + UserTokens::EXPIRE_TIME;
                $token->status = User::STATUS_INACTIVE;
                $token->token = Yii::$app->security->generateRandomString(64);
                $token->save();
            }

            $order = $this->order($user);
            $this->balance($this->total_price, $order);
            foreach ($this->models as $item) {
                /**
                 * @var $model Models
                 */
                $model = Models::findOne(['id' => $item['model_id']]);
                if ($model->getCount($this->store_id) < $item['count']) {
                    $this->addErrors([
                        'modelName' => $model->name,
                        'count' => $model->getCount($this->store_id)
                    ]);
                    return false;
                }
                $this->coming($model, $item, $order->id, $user, $order);
                $this->out($model, $item, $order->id, $order, $user);

                $this->ball = $this->ball + ($model->ball * $item['count']);
            }

            $user->updateAttributes(['ball' => $user->ball + $this->ball]);

            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw new \DomainException($exception->getMessage());
        }
        return $order;
    }

    /**
     * @param $user
     * @return \yii\rbac\Assignment
     * @throws \Exception
     */
    private function _createRole($user)
    {
        $role_name = User::ROLE_CLIENT;
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole($role_name);
        $auth->revokeAll($user->id);
        return $auth->assign($authorRole, $user->id);
    }

    private function order($user)
    {
        $userToken = User::getByToken();
        $order = new Order();
        $order->user_id = $user->id;
        $order->status = Product::STATUS_IMPLOMENTET;
        $order->price = $this->total_price;
        $order->store_id = $this->store_id;
        $order->outgo_data = $this->outgo_data;
        $order->created_user_id = $userToken->id;
        $order->save();
        return $order;
    }

    private function balance($price, $order)
    {
        $balance = new Balans();
        $balance->user_id = $order->user_id;
        $balance->from_user_id = $this->store_id;
        $balance->order_id = $order->id;
        $balance->income_outgo = Product::SALE;
        $balance->amount = $price;
        $balance->status = Balans::STATUS_APPROVED;
        if ($balance->save()) {
            return $balance;
        }
        $this->addError(json_decode($balance->errors));
        return false;
    }

    private function coming($model, $item, $order_id, $user, $order)
    {
        $product = new Product();
        $product->price = $model->price;
        $product->models_category_id = $model->category_id;
        $product->models_id = $model->id;
        $product->user_id = $user->id;
        $product->status = Product::STATUS_IMPLOMENTET;
        $product->coming_data = $this->outgo_data;
        $product->coming_outgo = Product::COMING;
        $product->from_user_id = $order->store_id;
        $product->count = $item['count'];
        $product->order_id = $order_id;
        if ($product->save()) {
            $this->createOrderClient($product, $model);
            return $product;
        }
        $this->addError(json_decode($product->errors));
        return false;
    }

    /**
     * @param $product
     * @param $model
     */
    public function createOrderClient($product, $model)
    {
        $query = Settings::find()->one();
        $price = $this->total_price * $query->course;

        $order_client = new OrderClient();
        $order_client->user_id = $product->user_id;
        $order_client->from_user_id = $this->store_id;
        $order_client->count = $product->count;
        $order_client->models_id = $model->id;
        $order_client->price = $price;
        $order_client->phone = $this->phone;
        $order_client->status = OrderClient::STATUS_ACTIVE;
        $order_client->category_id = $model->category_id;
        if ($order_client->save()) {
            $this->sendMessage($model, $order_client);
        }
    }

    /**
     * @param $model
     * @param $order_client
     * @return mixed
     */
    public function sendMessage($model, $order_client)
    {
        $query = Settings::find()->one();
        $price = $this->total_price * $query->course;
        $message = ("Покупка" .
            "\n " . "Модель: $model->name" .
            "\n " . "Количество: " . $order_client->count .
            "\n " . "Балл: " . $model->ball * $order_client->count .
            "\n " . "Цена: $price" . "сум");
        Yii::$app->playmobile->sendSms($order_client->phone, $message);
    }

    private function out($model, $item, $order_id, $order, $user)
    {
        $store = new Product();
        $store->user_id = $order->store_id;
        $store->coming_outgo = Product::OUTGO;
        $store->models_id = $model->id;
        $store->coming_data = $this->outgo_data;
        $store->status = Product::STATUS_IMPLOMENTET;
        $store->models_category_id = $model->category_id;
        $store->from_user_id = $user->id;
        $store->count = $item['count'];
        $store->price = $model->price;
        $store->order_id = $order_id;
        if ($store->save()) {
            return $store;
        }
        $this->addError(json_decode($store->errors));
        return false;
    }

}
