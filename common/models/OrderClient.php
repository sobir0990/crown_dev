<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order_client".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $from_user_id
 * @property int|null $models_id
 * @property int|null $category_id
 * @property int|null $price
 * @property int|null $count
 * @property string $phone
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class OrderClient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_client';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 9;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'from_user_id', 'models_id', 'category_id', 'price', 'count', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['user_id', 'from_user_id', 'models_id', 'category_id', 'price', 'count', 'status', 'created_at', 'updated_at'], 'integer'],
            [['phone'], 'string', 'max' => 254]
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
            'from_user_id' => 'From User ID',
            'models_id' => 'Models ID',
            'category_id' => 'Category ID',
            'price' => 'Price',
            'count' => 'Count',
            'phone' => 'Phone',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
