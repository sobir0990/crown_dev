<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_tokens".
 *
 * @property int $id
 * @property int $user_id
 * @property int $created_at
 * @property int $last_used_at
 * @property int $expires
 * @property int $user_agent
 * @property int $status
 * @property int $data
 * @property int $type
 * @property string $token
 * @property string $phone
 *
 * @property User $user
 */
class UserTokens extends ActiveRecord
{

    const EXPIRE_TIME = 5184000; //3600 * 24 * 30 * 2

    const STATUS_DELETED = 0;
    const STATUS_PHONE_APPROVE = 1;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const TYPE_APPROVE_PHONE = 1;
    const TYPE_AUTH = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['user_id', 'token'], 'required'],
            [['user_id', 'created_at', 'last_used_at', 'expires', 'user_agent', 'phone'], 'default', 'value' => null],
            [['user_id', 'created_at', 'last_used_at', 'expires', 'status', 'data', 'type'], 'integer'],
            [['user_agent'], 'string'],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    public function extraFields()
    {
        return [
            'user'
        ];
    }


    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'date_filter' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'last_used_at'],
                ],
                'value' => function ($event) {
                    $this->expires = time() + UserTokens::EXPIRE_TIME;
                    return time();
                }
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'last_used_at' => 'Last Used At',
            'expires' => 'Expires',
            'user_agent' => 'User Agent',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function setActive()
    {
        $this->updateAttributes(['status' => self::STATUS_ACTIVE]);
        return true;
    }

    /**
     * When token used, updates expires and last_used_at properties
     * @see User::findIdentityByAccessToken()
     */
    public function used()
    {
        $this->updateAttributes([
            'expires' => time() + UserTokens::EXPIRE_TIME,
            'last_used_at' => time(),
            'user_agent' => \Yii::$app->request->getUserAgent()
        ]);
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeValidate()
    {
        if (strlen($this->token) == 0) {
            if ($this->type == self::TYPE_APPROVE_PHONE) {
                $this->generateToken();
            }
            $this->token = Yii::$app->security->generateRandomString(32);
        }
        return parent::beforeValidate();
    }

    /**
     * @throws \yii\base\Exception
     */
    private function generateToken()
    {
        return $this->data = rand(1000, 9999);
    }

}
