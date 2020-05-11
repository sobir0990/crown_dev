<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order_products".
 *
 * @property int $order_id
 * @property int $product_id
 * @property int $product_user_id
 * @property int $product_models_id
 * @property int $product_models_category_id
 *
 * @property Categories $productModelsCategory
 * @property Models $productModels
 * @property Order $order
 * @property Product $product
 * @property User $productUser
 */
class OrderProducts extends \yii\db\ActiveRecord
{
    /**
     * @OA\Property(
     *   property="order_id",
     *   type="integer",
     *   description="Order ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="product_id",
     *   type="integer",
     *   description="Product ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="product_user_id",
     *   type="integer",
     *   description="Product User ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="product_models_id",
     *   type="integer",
     *   description="Product Models ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="product_models_category_id",
     *   type="integer",
     *   description="Product Models Category ID"
     * )
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'product_user_id', 'product_models_id', 'product_models_category_id'], 'default', 'value' => null],
            [['product_id', 'product_user_id', 'product_models_id', 'product_models_category_id'], 'integer'],
            [['product_models_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['product_models_category_id' => 'id']],
            [['product_models_id'], 'exist', 'skipOnError' => true, 'targetClass' => Models::className(), 'targetAttribute' => ['product_models_id' => 'id']],
//            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['product_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['product_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'product_user_id' => 'Product User ID',
            'product_models_id' => 'Product Models ID',
            'product_models_category_id' => 'Product Models Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductModelsCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'product_models_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductModels()
    {
        return $this->hasOne(Models::className(), ['id' => 'product_models_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'product_user_id']);
    }

    public function extraFields()
    {
        return [
            'productModelsCategory',
            'productModels',
            'order',
            'product',
            'user',
        ];
    }

    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            'productCount',
            'categoryCount',
            'total'
        ]);
    }


    public function getTotal()
    {
        $coming = Product::find()
            ->select('SUM(count) as productCount, SUM(price) as price')
            ->andWhere(['user_id' => $this->product_user_id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = Product::find()
            ->select('SUM(count) as productCount, SUM(price) as price')
            ->andWhere(['user_id' => $this->product_user_id])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $count = $coming['productCount'] - $outgo['productCount'];
        $price = $coming['price'] - $outgo['price'];
        $totalPrice = $price * $count;
        return array_merge(
            [
                'count' => $count,
                'price' => $price,
                'totalPrice' => $totalPrice
            ]
        );
    }

}
