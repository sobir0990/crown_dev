<?php


namespace common\modules\product\forms;

use common\modules\product\services\ProductServices;
use Yii;
use yii\base\Model;

class CreateProductForms extends Model
{
    public $price;
    public $selling_price;
    public $count;
    public $coming_outgo;
    public $user_id;
    public $models_id;
    public $models_category_id;
    public $coming_data;
    public $outgo_data;
    public $created_at;
    public $updated_at;
    public $from_user_id;
    public $status;

    public function rules()
    {
        return [
            [['count', 'coming_outgo', 'user_id', 'models_id', 'models_category_id',
                'coming_data', 'outgo_data', 'created_at', 'updated_at', 'from_user_id',
                'status'], 'integer'],
            [['user_id'], 'required'],
            [['price', 'selling_price'], 'safe']
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function create()
    {
        $requestParams = Yii::$app->request->getBodyParams();
        if (!is_array($requestParams) || count($requestParams) == 0) {
            throw new \DomainException('Incorrect data', 400);
        }

        /**
         * @var $productServices ProductServices
         */
        $productServices = Yii::$container->get(ProductServices::class);
        $model = $productServices->createStore($requestParams);
        return $model;

    }

}
