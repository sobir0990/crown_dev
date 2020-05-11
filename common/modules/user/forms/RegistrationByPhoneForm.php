<?php

namespace common\modules\user\forms;

use common\models\User;
use common\modules\user\services\UserServices;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class RegistrationForm
 * @package common\modules\users\forms
 * @property string $username
 * @property string $phone
 * @property string $email
 */

/**
 * @OA\Schema()
 */
class RegistrationByPhoneForm extends Model
{
    const PHONE_PATTERN = "/[0-9]+/";

    /**
     * @OA\Property(
     *   property="phone_and_email",
     *   type="string",
     *   description="Phone and email",
     * )
     */
    public $phone;

    /**
     * @OA\Property(
     *   property="agreement",
     *   type="boolean",
     *   description="Agreement"
     * )
     */
    public $agreement = true;

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['phone'], 'required'],
                [['phone'], 'validatePhone'],
                [['agreement'], 'agreementValidator']
            ]
        );
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function agreementValidator($attribute, $params)
    {
        if ($this->agreement == false) {
            $this->addError($attribute, __("To register, you must agree to the terms and conditions."));
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePhone($attribute, $params)
    {
        if (!($this->isPhone() && strlen($this->phone) == 12)) {
            $this->addError($attribute, __("You entered incorrect data"));
            return;
        }

    }

    /**
     * @return bool
     */
    public function isPhone(): bool
    {
        $this->phone = preg_replace("#[^0-9]#", null, $this->phone);
        return boolval(preg_match(self::PHONE_PATTERN, $this->phone));
    }

    /**
     * @return bool|User|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        /**
         * @var $userService UserServices
         */
        $userService = Yii::$container->get(UserServices::class);
        if ($this->isPhone()) {
            $userService->sendRequestPhoneApprove($this->phone);
        }
        return true;
    }

}
