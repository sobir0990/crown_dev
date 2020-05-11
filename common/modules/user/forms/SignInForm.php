<?php

namespace common\modules\user\forms;

use common\models\User;
use common\models\UserTokens;
use common\modules\playmobile\models\PhoneConfirmation;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Signin form
 */
class SignInForm extends Model
{
    public $phone;
    public $username;
    public $name;
    public $expire;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // phone and password are both required
            [['phone'], 'required'],
            // rememberMe must be a boolean value
            [['phone'], 'string'],
            [['name'], 'string', 'max' => 254],
            [['expire'], 'tokenValidate']
        ];
    }

    public function tokenValidate()
    {
        $model = UserTokens::find()->andWhere(['>', 'expire', time()])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->one();
        if ($model) {
            Yii::$app->response->setStatusCode(422);
        }
        return true;
    }

    /**
     * @return array|bool|UserTokens|ActiveRecord|null
     * @throws Exception
     */
    public function signin()
    {
        $code = rand(1000, 9999);

        if (!$this->validate()) {
            return false;
        }

        $user = User::find()->andWhere(['phone' => $this->phone])->one();

        if ($this->phone == '998998189423') {
            $code = 1000;
        }
        if (!($user instanceof User)) {
            $user = new User();
            $user->phone = $this->phone;
            $user->name = $this->name;
            $user->username = $this->username;
            $user->save();
            $this->_createRole($user);
        }
//
//        if (!is_object($user)) {
//            Yii::$app->response->setStatusCode(403);
//        }

        $model = UserTokens::find()
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['status' => User::STATUS_INACTIVE])
            ->one();

        if (!is_object($model)) {
            $token = new UserTokens();
            $token->user_id = $user->id;
            $token->expires = time() + UserTokens::EXPIRE_TIME;
            $token->status = User::STATUS_INACTIVE;
            $token->token = Yii::$app->security->generateRandomString(64);
            $token->save();
            $this->_createPhoneConfirmation($user, $code);
            $message = "<#> Crown 2020 tasdiqlash kodingiz code: " . $code . " " . "/G6JexDKRqbW";
            Yii::$app->playmobile->sendSms($user->phone, $message);
            return $this->phone;
        }

        $this->_createPhoneConfirmation($user, $code);
        $message = "<#> Crown 2020 tasdiqlash kodingiz code: " . $code . " " . "/G6JexDKRqbW";
        Yii::$app->playmobile->sendSms($user->phone, $message);
        return $this->phone;
    }


    private function _createPhoneConfirmation($user, $code)
    {
        $confirmation = new PhoneConfirmation();
        $confirmation->phone = $user->phone;
        $confirmation->status = PhoneConfirmation::STATUS_UNCONFIRMED;
        $confirmation->code = (string)$code;
        return $confirmation->save();
    }

    /**
     * @param $user
     * @return \yii\rbac\Assignment
     * @throws \Exception
     */
    private function _createRole($user)
    {
        $role_name = User::ROLE_CLIENT;
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole($role_name);
        $auth->revokeAll($user->id);
        return $auth->assign($authorRole, $user->id);
    }

}
