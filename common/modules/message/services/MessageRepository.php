<?php


namespace common\modules\message\services;


use common\models\User;
use common\modules\message\cron\SendMessage;
use common\modules\message\models\SmsMessage;
use common\modules\message\models\Messages;
use Yii;
use yii\web\NotFoundHttpException;

class MessageRepository
{

    /**
     * @api {{url}}{{version}}/sms-message/resend-sms/{message_id}
     * @param $id
     * @param $msg
     * @return array|\yii\db\ActiveRecord[]
     */
    public function returnSms($id)
    {
        $messages = SmsMessage::find()
            ->andWhere(['status' => SmsMessage::STATUS_NO_ACTIVE])
            ->andWhere(['message_id' => $id])
            ->each(100);

        foreach ($messages as $message) {
            \Yii::$app->queue->push(new SendMessage([
                'phone' => $message->phone,
                'message' => $message->message,
                'sms_message_id' => $message->id
            ]));
            return $message;
        }
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function getByID($id): User
    {
        /**
         * @var $user User
         */
        $user = User::findOne($id);
        if (!($user instanceof User)) {
            throw new NotFoundHttpException("User not founded");
        }
        return $user;
    }

    /**
     * @return int|null
     */
    public function resendSms()
    {
        /**
         * @var $message \common\modules\message\models\SmsMessage
         */
        $messages = SmsMessage::find()->andWhere(['status' => SmsMessage::STATUS_NO_ACTIVE])->all();
        if (count($messages) == 0) {
            return null;
        }
        $count = 0;
        foreach ($messages as $message) {
            \Yii::$app->queue->push(new SendMessage([
                'phone' => $message->phone,
                'message' => $message->message,
                'sms_message_id' => $message->id
            ]));
            $count++;
        }
        return $messages;
    }

    /**
     * @param null $id
     * @param $role
     * @param $msg
     * @return Messages
     * @throws NotFoundHttpException
     */
    public function sendMessage($id = null, $role, $msg)
    {
        if ($id !== null) {
            $message = new Messages();
            $message->message = $msg;
            $message->state = Messages::STATE_EMPTY;
            $message->user_id = Yii::$app->user->id;
            if (!$message->save()) {
                throw new \DomainException($message->errors, 422);
            }
            $user = $this->getByID($id);
            $this->sendSmsMessage($user, $message, $role);
//            $this->sendSmsPhone($message->message, $user);
            return $message;
        } else {
            $auth = Yii::$app->authManager;
            $ids = $auth->getUserIdsByRole($role);
            $message = new Messages();
            $message->message = $msg;
            $message->state = Messages::STATE_EMPTY;
            $message->user_id = Yii::$app->user->id;
            if (!$message->save()) {
                throw new \DomainException($message->errors, 422);
            }
            foreach ($ids as $id) {
                $user = $this->getByID($id);
                $this->sendSmsMessage($user, $message, $role);
            }
            return $message;
        }

    }


    public function sendSmsMessage(User $user, $message, $role)
    {
        $sms_message = new SmsMessage();
        $sms_message->phone = $user->phone;
        $sms_message->user_id = $user->id;
        $sms_message->message_id = $message->id;
        $sms_message->message = $message->message;
        $sms_message->role = $role;
        $sms_message->status = SmsMessage::STATUS_NO_ACTIVE;
        if (!($sms_message->save())) {
            Yii::$app->response->statusCode = 422;
            return $sms_message->errors;
        }

        \Yii::$app->queue->push(new SendMessage([
            'phone' => $user->phone,
            'message' => $message->message,
            'sms_message_id' => $sms_message->id
        ]));
    }

    private function sendSmsPhone($message, $user)
    {
        \Yii::$app->queue->push(new SendMessage([
            'phone' => $user->phone,
            'message' => $message->message,
            'sms_message_id' => $message->id
        ]));
    }
}
