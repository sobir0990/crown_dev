<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "balans".
 *
 * @property int $id
 * @property double $amount
 * @property int $income_outgo
 * @property int $user_id
 * @property int $from_user_id
 * @property int $order_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 * @property string $comment
 *
 * @property Order $order
 * @property User $user
 */
class Balans extends \yii\db\ActiveRecord
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
     *   property="amount",
     *   type="double",
     *   description="Amount"
     * )
     */
    /**
     * @OA\Property(
     *   property="income_outgo",
     *   type="integer",
     *   description="Income Outgo"
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
     *   property="order_id",
     *   type="integer",
     *   description="Order ID"
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
    const STATUS_NOT_APPROVED = 0;
    const STATUS_REJECTED = 2;
    const STATUS_APPROVED = 1;
    const STATUS_BRON = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'balans';
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
    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['income_outgo', 'user_id', 'order_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['income_outgo', 'user_id', 'from_user_id', 'order_id', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string', 'max' => 254],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'income_outgo' => 'Income Outgo',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function fields()
        {
            return ArrayHelper::merge(parent::fields(), [
                'comment',
            ]);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function extraFields()
    {
        $fields = parent::extraFields();
        $fields['order'] = "order";
        $fields['user'] = "user";
        return $fields;
    }
}
