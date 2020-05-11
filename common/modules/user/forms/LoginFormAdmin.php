<?php

namespace common\modules\user\forms;

use common\models\User;
use common\models\UserTokens;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package common\modules\users\forms
 * @property-read User $user
 */

/**
 * @OA\Schema()
 */
class LoginFormAdmin extends Model
{


    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    public function rules()
    {
        return [
            // phone and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            [['username', 'password'], 'string'],
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }


    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function signin()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user_token = new UserTokens();
            $user_token->user_id = $user->id;
            $user_token->expires = time() + UserTokens::EXPIRE_TIME;
            $user_token->token = \Yii::$app->security->generateRandomString(32);
            $user_token->save();
            return $user;
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'username' => __("Username","login"),
            'password' => __("Password", "login"),
            'rememberMe' => __("Remember Me","login")
        ];
    }
}