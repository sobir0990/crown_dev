<?php

namespace common\modules\playmobile\models;

use Yii;

/**
 * This is the model class for table "smslog".
 *
 * @property int $id
 * @property string $recipient
 * @property int $message_id
 * @property string $originator
 * @property string $text
 * @property int $status
 */
class Smslog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smslog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recipient'], 'required'],
            [['message_id', 'status'], 'default', 'value' => null],
            [['message_id', 'status'], 'integer'],
            [['recipient'], 'string', 'max' => 15],
            [['originator'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 160],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipient' => 'Recipient',
            'message_id' => 'Message ID',
            'originator' => 'Originator',
            'text' => 'Text',
            'status' => 'Status',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\playmobile\models\query\SmslogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\playmobile\models\query\SmslogQuery(get_called_class());
    }
}
