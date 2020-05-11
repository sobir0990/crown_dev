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

class SignInClientForms extends Model
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
    public function signInClient()
    {
        $code = rand(1000, 9999);

        if (!$this->validate()) {
            return false;
        }
        $auth = Yii::$app->authManager;
        $client_id = $auth->getUserIdsByRole(User::ROLE_CLIENT);
        $user = User::find()->andWhere(['phone' => $this->phone])->andWhere(['id' => $client_id])->one();

        if (!($user instanceof User)) {
            $user = new User();
            $user->phone = $this->phone;
            $user->name = $this->name;
            $user->save();
            $this->_createRole($user);
        }

        $model = UserTokens::find()
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['status' => User::STATUS_INACTIVE])
            ->one();

        if (!is_object($model)) {
            $this->_userTokens($user, $code);
        }

        $this->_createPhoneConfirmation($user, $code);
        $message = "<#> Crown 2020 tasdiqlash kodingiz code: " . $code;
        Yii::$app->playmobile->sendSms($user->phone, $message);
        return $this->phone;
    }

    /**
     * @param $user
     * @param $code
     * @return mixed
     * @throws Exception
     */
    private function _userTokens($user, $code)
    {
            $token = new UserTokens();
            $token->user_id = $user->id;
            $token->expires = time() + UserTokens::EXPIRE_TIME;
            $token->status = User::STATUS_INACTIVE;
            $token->token = Yii::$app->security->generateRandomString(64);
            $token->save();
            $this->_createPhoneConfirmation($user, $code);
            $message = "<#> Crown 2020 tasdiqlash kodingiz code: " . $code;
            Yii::$app->playmobile->sendSms($user->phone, $message);
            return $user->phone;

    }

    /**
     * @param $user
     * @param $code
     */
    private function _createPhoneConfirmation($user, $code)
    {
        $confirmation = new PhoneConfirmation();
        $confirmation->phone = $user->phone;
        $confirmation->status = PhoneConfirmation::STATUS_UNCONFIRMED;
        $confirmation->code = (string)$code;
        $confirmation->save();
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
