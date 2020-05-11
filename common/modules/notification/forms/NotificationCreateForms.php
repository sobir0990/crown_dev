<?php

namespace common\modules\notification\forms;

use common\models\User;
use common\modules\notification\models\Notifications;

class NotificationCreateForms extends \yii\base\Model
{

    public $message;
    public $title;
    public $user_id;
    public $status;
    public $type;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            [['message'], 'string'],
            [['status', 'user_id', 'type', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 254],
        ];
    }

    public function create()
    {

        $model = new Notifications();
        $model->title = $this->title;
        $model->message = $this->message;
        $model->status = $this->status;
        if ($model->save()) {
            if (is_array($this->user_id)) {
                $model->unlinkAll('notifications', true);
                foreach ($this->user_id as $item) {
                    $user = Notifications::findOne($item);
                    $model->link('notifications', $user);
                }
            } else {
                $model->unlinkAll('notifications', true);
                $user = Notifications::findOne($this->user_id);
                $model->link('notifications', $user);
            }
        }
        return $model;

    }
}
