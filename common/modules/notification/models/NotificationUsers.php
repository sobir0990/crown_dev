<?php

namespace common\modules\notification\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notification_users".
 *
 * @property int $notification_id
 * @property int $user_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Notifications $notification
 * @property User $user
 */

/**
 * @OA\Schema(
 *     description="include=notification,user"
 * )
 */
class NotificationUsers extends \yii\db\ActiveRecord
{
    /**
     * @OA\Property(
     *   property="notification_id",
     *   type="integer",
     *   description="Notification ID"
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

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notification_id', 'user_id', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['notification_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notifications::className(), 'targetAttribute' => ['notification_id' => 'notification_id']],
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
            'notification_id' => 'Notification ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notifications::className(), ['notification_id' => 'notification_id']);
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
        $fields['notification'] = "notification";
        $fields['user'] = "user";
        return $fields;
    }
}
