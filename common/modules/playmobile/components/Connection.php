<?php
/**
 * @author Izzat <i.rakhmatov@list.ru>
 * @package advanced
 */

namespace common\modules\playmobile\components;


use common\modules\playmobile\models\Smslog;
use GuzzleHttp\Client;
use yii\base\Component;

class Connection extends Component
{

    public $username;
    public $password;
    public $originator = '3700';
    public $baseUrl = 'http://91.204.239.44/broker-api';
//    public $baseUrl = 'http://91.204.239.42:8083/broker-api';

    /**.
     * @param $recipient
     * @param $text
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSms($recipient, $text)
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $message_id = date("Y-m-d-H-i-s");

        $message = array(
            'messages' => array(
                array(
                    'recipient' => $recipient,
                    'message-id' => $message_id,
                    'sms' => array(
                        'originator' => $this->originator,
                        'content' => array(
                            'text' => $text
                        )
                    )
                ),
            )
        );
        $model = new Smslog();
        $model->recipient = $recipient;
        $model->originator = $this->originator;
        $model->message_id = $message_id;
        $model->text = $text;
        $model->save();


        try {
            $client->request('POST', "{$this->baseUrl}/send", [
                'auth' => array($this->username, $this->password),
                'body' => json_encode($message)
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function getAuthorizationToken()
    {
        return base64_encode("{$this->username}/{$this->password}");
    }

}
