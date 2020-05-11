<?php

namespace common\modules\user\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class ChangePhoneForm
 * @package common\modules\users\forms
 */
class ChangePhoneForm extends Model
{
    const PHONE_PATTERN = "/[0-9]+/";

    public $password;
    public $phone;

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['phone', 'password'], 'required'],
            [['phone'], 'validatorPhone'],
            [['password'], 'validatorPassword'],
        ]);
    }

    /**
     * @param $attribute
     * @param $params
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function validatorPhone($attribute, $params)
    {
        if (!preg_match(static::PHONE_PATTERN, $this->phone)) {
            $this->addError($attribute, "Phone is not valid");
        }
        /**
         * @var $repository UserRepository
         */
        $repository = Yii::$container->get(UserRepository::class);

        if ($repository->existPhone($this->phone)) {
            $this->addError($attribute, __("Phone is exist"));
            return;
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function validatorPassword($attribute, $params)
    {
        /**
         * @var $userServices UserServices
         */
        $userServices = Yii::$container->get(UserServices::class);

        $dto = new UserValidatePasswordDTO();
        $dto->password = $this->$attribute;
        $dto->user_id = Yii::$app->user->id;

        try {
            $userServices->validatePassword($dto);
        } catch (PasswordWrongException $exception) {
            $this->addError("username", Yii::t("user", "Password is wrong"));
        }
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function sendRequest()
    {
        if (!$this->validate()) {
            return false;
        }

        /**
         * @var $service UserServices
         */
        $service = Yii::$container->get(UserServices::class);
        $service->sendRequestPhoneApprove($this->phone, Yii::$app->user->id);

        return true;
    }

}