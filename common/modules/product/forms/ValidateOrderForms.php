<?php


namespace common\modules\product\forms;


use common\models\Balans;
use common\models\Models;
use common\models\Order;
use common\models\OrderClient;
use common\models\Product;
use common\models\User;
use Yii;
use yii\base\Model;

class ValidateOrderForms extends Model
{

    public $store_id;
    public $outgo_data;
    public $models;
    public $user_id;
    public $status;
    public $total_price;
    public $price;
    public $phone;

    public function rules()
    {
        return [
            [['store_id', 'outgo_data', 'price', 'status', 'user_id', 'total_price'], 'integer'],
            [['models'], 'safe'],
            [['phone'], 'string', 'max' => 254]
        ];
    }


    /**
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validateOrder()
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        $requestParams = Yii::$app->getRequest()->getBodyParams();
        $data = [];
        $model = Models::findOne(['id' => $requestParams['model_id']]);
        if (!is_object($model)) {
            throw new \DomainException('Not found model ' . $requestParams['model_id']);
        }
        $count = $model->getCount($this->store_id);
        if ($count >= $requestParams['count'] && $count !== 0) {
            return true;
        }
        Yii::$app->response->statusCode = 422;
        return $data[] = [
            'id' => $model->id,
            'modelName' => $model->name,
            'count' => $model->getCount($this->store_id)
        ];
    }

}
