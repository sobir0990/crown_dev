<?php

namespace common\modules\user\forms;

use common\models\User;
use common\models\UserTokens;
use common\modules\user\repositories\UserRepository;
use common\modules\user\services\UserServices;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class ApprovePhoneForm
 * @package common\modules\user\forms
 */
class ApprovePhoneForm extends Model
{
    public $code;

    public $phone;

    /**
     * @var UserTokens
     */
    private $token;

    private $role = 'client';

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['code', 'phone'], 'required'],
            [['code'], 'integer'],
            [['code'], 'validateCode']
        ]);
    }

    /**
     * @param $attribute
     * @param $params
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function validateCode($attribute, $params)
    {
        /**
         * @var $service UserServices
         */
        $service = Yii::$container->get(UserServices::class);

        if (!$this->token = $service->approvePhone($this->phone, $this->code)) {
            $this->addError($attribute, __("Code is not valid"));
        }
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $this->phone = preg_replace("#[^0-9]#", null, $this->phone);
        $this->code = preg_replace("#[^0-9]#", null, $this->code);

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool|UserTokens
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\web\NotFoundHttpException
     */
    public function approve()
    {
        if(!$this->validate()){
            return false;
        }

        /**
         * @var $repositoryUser UserRepository
         */
        $repositoryUser = Yii::$container->get(UserRepository::class);
        /**
         * @var $userService UserServices
         */
        $userService = Yii::$container->get(UserServices::class);

        $user = $repositoryUser->approvePhone($this->token);

        if (is_array($user)) {
            $resultErrors = $user;
            foreach ($resultErrors as $resultAttr => $resultError) {
                foreach ($resultError as $error) {
                    $this->addError('phone', $error);
                }
            }
            return false;
        }

        $userService->addRoleToUser($this->role, $user);

        if ($user->username == null) {
            $this->token->status = 2;
            return $this->token;
        }
        return $this->token;

    }
}
