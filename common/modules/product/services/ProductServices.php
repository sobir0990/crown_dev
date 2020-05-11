<?php

namespace common\modules\product\services;

use common\models\Balans;
use common\models\Models;
use common\models\Order;
use common\models\OrderProducts;
use common\models\Product;
use Yii;
use yii\web\NotFoundHttpException;

class ProductServices
{

    /**
     * @param $id
     * @return Product
     * @throws NotFoundHttpException
     */
    public function getByID($id): Product
    {
        /**
         * @var $product Product
         */
        $product = Product::findOne($id);
        if (!($product instanceof Product)) {
            throw new NotFoundHttpException("Product not founded");
        }
        return $product;
    }

    /**
     * @param $requestParams
     * @return mixed
     */

    public function createStore($requestParams)
    {
//        $transaction = Yii::$app->db->beginTransaction();
//        try {
        foreach ($requestParams['models'] as $item) {
            $models = Models::find()->andWhere(['id' => $item['model_id']])->one();
            $products = new Product();
            $products->price = $models->price;
            $products->models_category_id = $models->category_id;
            $products->models_id = $item['model_id'];
            $products->user_id = $requestParams['user_id'];
            $products->coming_outgo = $requestParams['coming_outgo'];
            $products->from_user_id = $requestParams['from_user_id'];
            $products->count = $item['count'];
            $products->save();
        }
        return $requestParams;
//        } catch (\Exception $exception) {
//            $transaction->rollBack();
//            return $this->_product->getErrors();
//        }
    }

}
