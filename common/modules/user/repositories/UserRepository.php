<?php

namespace common\modules\user\repositories;

use common\models\User;
use common\models\UserTokens;
use common\modules\user\dto\CreateUserDTO;
use common\modules\user\helpers\UserHelper;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class UserRepository
 * @package common\modules\users\repositories
 */
class UserRepository
{

    /**
     * @param $username
     * @return User
     * @throws NotFoundHttpException
     */
    public function getForAuth($username): User
    {
        $user = User::find()
            ->orWhere(['username' => $username])
            ->orWhere(['phone' => $username])
            ->orWhere(['email' => $username])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->one();
        if (!($user instanceof User)) {
            throw new NotFoundHttpException("User 2not founded");
        }
        return $user;
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function getByID($id): User
    {
        /**
         * @var $user User
         */
        $user = User::findOne($id);
        if (!($user instanceof User)) {
            throw new NotFoundHttpException("User not founded");
        }
        return $user;
    }


    public function getByIDClient($id): User
    {
        /**
         * @var $user User
         */
        $auth = Yii::$app->authManager;
        $client_id = $auth->getUserIdsByRole(User::ROLE_CLIENT);
        $user = User::find()->andWhere(['id' => $id])->andWhere(['id' => $client_id])->one();
        if (!($user instanceof User)) {
            throw new NotFoundHttpException("User not founded");
        }
        return $user;
    }


    /**
     * @return User|null
     */
    public function current(): ?User
    {
        $user_id = Yii::$app->user->identity->getId();
        $user = \Yii::$app->cache->get("userById{$user_id}");
        if ($user === false) {
            $user = User::findOne(Yii::$app->user->identity->getId());
            \Yii::$app->cache->set("userById{$user_id}", $user, 86400);
        }
        return $user;
    }

    /**
     * @param $token_hash
     * @return User|null
     * @throws NotFoundHttpException
     */
    public function getUserByValidToken($token_hash): ?User
    {
        $token = UserTokens::find()->andWhere(['token' => $token_hash])->one();
        if (!($token instanceof UserTokens)) {
            return null;
        }
        return $this->getByID($token->user_id);
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function getRoleById($id)
    {
        /**
         * @var $user User
         */
        $user = User::findOne($id);
        if (!($user instanceof User)) {
            throw new NotFoundHttpException("User not founded");
        }
        $auth = Yii::$app->authManager;
        $role = $auth->getRolesByUser($user->id);
        if (count($role) > 1) {
            return array_pop($role)->name;
        }
        return array_shift($role)->name;
    }

    /**
     * @param $phone
     * @return bool
     */
    public function existPhone($phone)
    {
        return is_object(User::find()
            ->andWhere(['phone' => $phone])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->one());
    }

    /**
     * @param $phone
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByPhone($phone)
    {
        return (User::find()->where(['phone' => $phone])->one());
    }

    /**
     * @param $id
     * @param $password
     * @return mixed
     */
    public function changePassword($id, $password)
    {
        /**
         * @var $user User
         */
        $user = User::findOne($id);
        return $user->changePassword($password);
    }

    /**
     * @param UserTokens $token
     * @return array|User|null
     */
    public function approvePhone(UserTokens $token)
    {
        if ($token->user_id == null) {

            $createUserDTO = new CreateUserDTO();
            $createUserDTO->phone = $token->phone;
            $createUserDTO->password = $this->generatorForPassword();
            $createUserDTO->password_repeat = $createUserDTO->password;
            $user = $this->getOrCreate($createUserDTO);

            if (is_array($user)) {
                return $user;
            }

            $token->updateAttributes(['user_id' => $user->id]);
            $token->setActive();
            if ($user->status !== User::STATUS_ACTIVE){
                $user->setActivated();
            }
            /** @var PaymentInterface $payment */
            return $user;
        } else {
            $token->setActive();
            $user = User::findOne($token->user_id);
            return $user;
        }

    }

    public function generatorForPassword($length = 4)
    {
        return UserHelper::generatorForPassword($length);
    }

    /**
     * @param CreateUserDTO $dto
     * @return array|User|null
     */
    public function getOrCreate(CreateUserDTO $dto)
    {
        $user = $this->getByPhone($dto->phone);
        if (is_object($user)) {
            return $user;
        }
        $user = new User();
        $user->phone = $dto->phone;
        $user->password = $dto->password;
//        $user->scenario = User::SCENARIO_UPDATE;

        if ($user->save()) {
            return $user;
        }
        return $user->getErrors();
    }

    public function getByPhone($phone)
    {
        /**
         * @var $user User
         */
        $user = User::findOne(['phone' => $phone]);
        if (!($user instanceof User)) {
            return null;
        }
        return $user;
    }

    /**
     * @param CreateUserDTO $dto
     * @return User|array
     */
    public function create(CreateUserDTO $dto)
    {
        $user = new User();
        $user->phone = $dto->phone;
        $user->password = $dto->password;
//        $user->scenario = User::SCENARIO_UPDATE;

        if ($user->save()) {
            return $user;
        }
        return $user->getErrors();
    }

    /**
     * @param User $user
     * @param      $phone
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function updatePhone(User $user, $phone)
    {
//        /** @var PaymentInterface $payment */
//        $payment = Yii::$container->get(PaymentInterface::class);
//        $payment->changePhone($user->phone, $phone);
        if (!($user->updateAttributes(['phone' => $phone]) == 1)) {
            throw new \DomainException('Can\'t change phone in billing');
        }
        return true;
    }

    /**
     * @param $id
     * @return User|null
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteAccount($id)
    {
        $user = User::findOne($id);
        if (!($user instanceof User)) {
            throw new \DomainException("User is not founded");
        }

        $user->delete();
        return $user;
    }


}
