<?php

namespace common\modules\notification\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notifications".
 *
 * @property int $notification_id
 * @property string $title
 * @property string $message
 * @property int $status
 * @property int $user_id
 * @property int $type
 * @property int $created_at
 * @property int $updated_at
 *
 * @property NotificationUsers[] $notificationUsers
 */

class Notifications extends \yii\db\ActiveRecord
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
     *   property="title",
     *   type="string",
     *   description="Title"
     * )
     */
    /**
     * @OA\Property(
     *   property="message",
     *   type="string",
     *   description="Message"
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
     *   property="user_id",
     *   type="integer",
     *   description="User ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="type",
     *   type="integer",
     *   description="Type"
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
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['status', 'user_id', 'type', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 254],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'notification_id' => 'Notification ID',
            'title' => 'Title',
            'message' => 'Message',
            'status' => 'Status',
            'user_id' => 'User ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
          TimestampBehavior::className()
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationUsers()
    {
        return $this->hasMany(NotificationUsers::className(), ['notification_id' => 'notification_id']);
    }


    public function getNotifications()
    {
        return $this->hasOne(Notifications::class, ['notification_id' => 'user_id'])->via('notificationUsers');
    }

    public function extraFields()
    {
        $fields = parent::extraFields();
        $fields['notificationUsers'] = "notificationUsers";
        return $fields;
    }
}
