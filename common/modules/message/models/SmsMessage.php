<?php

namespace common\modules\message\models;


use api\modules\v1\controllers\SmsMessageController;
use common\models\User;
use common\modules\translation\models\Message;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sms_message".
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 * @property string $message
 * @property string $message_id
 * @property int $status
 * @property int $role
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class SmsMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'sms_message';
    }

    const  STATUS_ACTIVE = 10;
    const  STATUS_NO_ACTIVE = 1;
//    public $message;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['user_id', 'status', 'created_at', 'updated_at', 'message_id'], 'integer'],
            [['phone', 'role'], 'string'],
            [['message'], 'string', 'max' => 254],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
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
            'message' => 'Message',
            'role' => 'Role',
            'phone' => 'Phone',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function fields()
    {
        return array(
            [
                'user' => function(){
                    return $this->getUser()->one();
                },
            ]
        );
    }


    public function extraFields()
    {
        return [
            'user' => function(){
            return $this->getUser()->one();
            },
            'message' => function(){
            return $this->getMessage()->one();
            }
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getMessage()
    {
        return $this->hasOne(Messages::className(), ['id' => 'message_id']);
    }
}
