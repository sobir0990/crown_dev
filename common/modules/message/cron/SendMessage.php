<?php

namespace common\modules\message\cron;


use common\models\User;
use common\modules\message\models\SmsMessage;
use jakharbek\sms\interfaces\SmsSenderInterface;
use Yii;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\queue\JobInterface;

class SendMessage extends BaseObject implements JobInterface
{
    public $phone;
    public $message;
    public $sms_message_id;
    public $sms_message;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|string|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function execute($queue)
    {
        /**
         * @var $sms SmsSenderInterface
         */
        $sms = Yii::$container->get(SmsSenderInterface::class);
        try
        {
            $sms->sendSms($this->phone, $this->message);
            $message = SmsMessage::findOne($this->sms_message_id);
            if (is_object($message)) {
                $message->updateAttributes(['status' => SmsMessage::STATUS_ACTIVE]);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }
}
