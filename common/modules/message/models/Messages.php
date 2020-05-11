<?php

namespace common\modules\message\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $message
 * @property int $status
 * @property int $state
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 *
 * @property User $user
 */
class Messages extends \yii\db\ActiveRecord
{
    const STATE_FULL = 1;
    const STATE_EMPTY = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'user_id'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at', 'user_id', 'state'], 'integer'],
            [['message'], 'string', 'max' => 254],
            [['state'], 'default', 'value' => 0],
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
            'message' => 'Message',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
