<?php

namespace api\modules\v1\forms;

use common\models\Reference;
use yii\base\Model;
use yii\httpclient\Client;

class ReferenceForm extends Model
{

    /**
     * @var
     */
    public $name;
    public $phone;
    public $theme;
    public $description;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['name', 'phone', 'theme'], 'string', 'max' => 254],
        ];
    }

    /**
     * @param $chatId
     * @param $text
     * @throws \yii\httpclient\Exception
     */
    public function sendMessageToTelegram($chatId, $text)
    {
        $bot_token = "939552448:AAGQwV1UqD6YuuonmP12Q_NUM6bmlBcdWLw";
        $client = new Client();
        $client->get("https://api.telegram.org/bot$bot_token/sendMessage", [
            "chat_id" => $chatId,
            "text" => $text,
            "parse_mode" => "HTML"
        ])->send();
    }

    /**
     * @return bool|Reference
     * @throws \yii\httpclient\Exception
     */
    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        $model = new Reference();
        $model->setAttributes($this->attributes);
        $model->name = $this->name;
        $model->description = $this->description;
        $model->phone = $this->phone;
        if (!($model->save())) {
            throw new \DomainException("Reference is not created");
        }
        $text = ("<b class='text-center text-danger'>MUROJAAT</b>
             \n " . "<b>Ism: </b>". $model->name .
            "\n " . "<b>Tel: </b>". $model->phone .
            "\n " . "<b>Xabar: </b>$model->description");
        $chatIds = array(
            -379173550,
        );
        foreach ($chatIds as $chatId) {
            $this->sendMessageToTelegram($chatId, $text);
        }
        return $model;

    }


}

?>
