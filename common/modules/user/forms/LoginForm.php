<?php

namespace common\modules\user\forms;

use common\models\User;
use common\modules\user\dto\UserValidatePasswordDTO;
use common\modules\user\repositories\UserRepository;
use common\modules\user\services\UserServices;
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
class LoginForm extends Model
{

    /**
     * @OA\Property(
     *   property="phone",
     *   type="string",
     *   description="Phone",
     * )
     */
    public $phone;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['phone'], 'required']
        ];
    }

    /**
     * @return bool|\common\models\UserTokens
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function getToken()
    {

        /**
         * @var $userServices UserServices
         */
        $userServices = Yii::$container->get(UserServices::class);

        $user = $this->user;

        if(!$user){return false;}

        if ($this->validate()) {
            return $userServices->createToken($user);
        }

        return false;
    }

    /**
     * @return bool|User
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function getUser()
    {
        /**
         * @var $userRepository UserRepository
         */
        $userRepository = Yii::$container->get(UserRepository::class);
        try {
            $temp = preg_replace('/\D+/', '', $this->phone);
            if ($temp !== '') $this->phone = $temp;
            return $userRepository->getForAuth($this->phone);
        }catch (\Exception $exception){
            $this->addError("username",Yii::t("user","Login or Password are wrong"));
            return false;
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => __("Username","login"),
            'password' => __("Password", "login"),
            'rememberMe' => __("Remember Me","login")
        ];
    }
}
