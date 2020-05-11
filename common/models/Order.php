<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property double $price
 * @property int $created_at
 * @property int $updated_at
 * @property int $store_id
 * @property int $outgo_data
 * @property int $created_user_id
 *
 * @property User $user
 * @property OrderProducts $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     *   description="ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="user_id",
     *   type="integer",
     *   description="User ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="status",
     *   type="integer",
     *   description="Status"
     * )
     */
    /**
     * @OA\Property(
     *   property="created_at",
     *   type="integer",
     *   description="Created At"
     * )
     */
    /**
     * @OA\Property(
     *   property="updated_at",
     *   type="integer",
     *   description="Updated At"
     * )
     */

    const STATUS_REJECTED = 0; //Отклонено
    const STATUS_ARMORED = 3; //Бронировано
    const STATUS_PROCESSING = 2; // В обработке
    const STATUS_IMPLOMENTET = 1; //Реализовано

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['user_id', 'status', 'created_at', 'updated_at', 'store_id', 'outgo_data', 'created_user_id'], 'integer'],
            [['price'], 'double'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(User::className(), ['id' => 'store_id']);
    }

    public function extraFields()
    {
        return [
            'user',
            'statistics',
            'products',
            'store'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['order_id' => 'id'])->onCondition(['coming_outgo' => Product::OUTGO]);
    }

    public function getStatistics()
    {
        $data = [];
        $query = Product::find()
//            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['order_id' => $this->id, 'coming_outgo' => Product::OUTGO])->distinct();

        $data['category_count'] = $query->count();

        $product_count = $query->select('SUM(count) as product_count')->asArray()->one();
        $data['product_count'] = $product_count['product_count'];
        return $data;
    }
}
