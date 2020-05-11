<?php

namespace common\modules\user\services;

use common\models\AuthAssignment;
use common\models\User;
use common\modules\token\dto\CreateTokenDto;
use common\models\UserTokens;
use common\modules\token\factory\TokenFactory;
use common\modules\user\dto\UserValidatePasswordDTO;
use common\modules\user\repositories\UserRepository;
use jakharbek\sms\interfaces\SmsSenderInterface;
use Yii;

/**
 * Class UserServices
 * @package common\modules\users\services
 */
class UserServices
{

    /**
     * @param UserValidatePasswordDTO $dto
     * @return bool
     * @throws \yii\base\Exception
     */
    public function validatePassword(UserValidatePasswordDTO $dto)
    {
        $repositoryUser = new UserRepository();
        $user = $repositoryUser->getByID($dto->id);
        if (Yii::$app->security->validatePassword($dto->password, $user->password_hash)) {
            return true;
        }
        throw new \DomainException("Password is wrong", 400);
    }


    /**
     * @param User $user
     * @return UserTokens
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function createToken(User $user): UserTokens
    {
        /**
         * @var $tokenFactory TokenFactory
         */
        $tokenFactory = Yii::$container->get(TokenFactory::class);

        $dto = new CreateTokenDto();
        $dto->user_id = $user->id;
        $dto->type = UserTokens::TYPE_AUTH;
        $dto->status = UserTokens::STATUS_ACTIVE;
        $dto->expire_at = (86400 * 30);

        return $tokenFactory::create($dto);
    }

    /**
     * @param $phone
     * @param $code
     * @return bool|UserTokens
     */
    public function approvePhone($phone, $code)
    {
        /**
         * @var $token UserTokens
         */
        $token = UserTokens::find()->andWhere(['phone' => $phone, 'data' => $code, 'status' => UserTokens::STATUS_PHONE_APPROVE])->one();
        if (is_object($token)) {
            return $token;
        }

        return false;
    }

    /**
     * @param User $user
     * @param $phone
     * @return bool
     * @throws \Exception
     */
    public function changePhoneRequest(User $user, $phone)
    {
        return $this->sendRequestPhoneApprove($phone, $user->user_id);
    }

    public function logoutByToken($token)
    {
        $t = UserTokens::find()->andWhere(['token' => $token])->one();
        $t->updateAttributes(['status' => UserTokens::STATUS_INACTIVE]);
        return true;
    }

    /**
     * @param $phone
     * @param null $user_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function sendRequestPhoneApprove($phone, $user_id = null)
    {
        $tokenFactory = new TokenFactory();
        $dto = new CreateTokenDto();
        $dto->user_id = $user_id;
        $dto->type = UserTokens::TYPE_APPROVE_PHONE;
        $dto->status = UserTokens::STATUS_PHONE_APPROVE;
        $dto->phone = $phone;
        $token = $tokenFactory::create($dto);
        /**
         * @var $sms SmsSenderInterface
         */
        $sms = Yii::$container->get(SmsSenderInterface::class);
        $msg = "<#> Sizning tasdiqlash kodingiz code: {$token->data}
qPmAH9gaU11";
        $sms->sendSms($phone, $msg);
        return true;
    }

    /**
     * @param $role_string
     * @param $user
     * @return User
     * @throws \Exception
     */
//    public function addRoleToUser($role_string, $user): User
//    {
//        $auth = Yii::$app->authManager;
//        $role = $auth->getRole($role_string);
//        if (is_object($role)) {
////            $auth->revokeAll($user->id);
//            $auth->assign($role, $user->id);
//        }
//        return $user;
//    }

    public function addRoleToUser($role_string, $user): User
    {
        $assigment = AuthAssignment::find()
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['not',['item_name' => null]])
            ->one();
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($role_string);
        if (!$assigment){
            $auth->assign($role, $user->id);
        }
            return $user;
    }

}
