<?php

namespace common\modules\playmobile\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "phone_confirmation".
 *
 * @property int $id
 * @property string $phone
 * @property string $code
 * @property int $status
 * @property int $created_at
 */
class PhoneConfirmation extends \yii\db\ActiveRecord
{

    const STATUS_UNCONFIRMED = 0;
    const STATUS_CONFIRMED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phone_confirmation';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'time' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ]
            ]

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at'], 'default', 'value' => null],
            [['status', 'created_at'], 'integer'],
            [['phone'], 'string', 'max' => 15],
            [['code'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'code' => 'Code',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\playmobile\models\query\PhoneConfirmationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\playmobile\models\query\PhoneConfirmationQuery(get_called_class());
    }
}
