<?php

namespace common\modules\user\forms;

use common\models\User;
use common\models\UserTokens;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\rbac\Role;

class CreateUserForm extends Model
{

    public $username;
    public $password;
    public $parent_id;
    public $files;
    public $name;
    public $address;
    public $email;
    public $phone;
    public $status;
    public $ball;
    public $pc;
    public $bank;
    public $mfo;
    public $inn;
    public $is_store;
    public $is_main;
    public $oked;
    public $region_id;
    public $created_at;
    public $updated_at;
    public $district_id;
    public $role;

    public $longitude;
    public $latitude;
    public $working_hours;

    /**
     * @return User
     */
    private $_user;


    public function rules()
    {
        return [
            [['username', 'name', 'files', 'ball', 'address', 'phone', 'working_hours'], 'string', 'max' => 254],
            [['parent_id','is_store', 'is_main', 'created_at', 'updated_at', 'region_id', 'district_id'], 'integer'],
            [['pc', 'mfo', 'inn', 'oked'], 'string', 'max' => 24],
            [['longitude', 'latitude'], 'string', 'max' => 254],
            [['is_main'], 'default', 'value' => 0],
//            ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This phone has already been taken'],
            [['phone'], 'phoneValidator'],
//            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email has already been taken'],
            [['password', 'email', 'bank', 'role'], 'string', 'max' => 255],
            [['status'], 'integer'],
        ];
    }


    public function phoneValidator($attributes)
    {
        $res = User::find()->andWhere(['phone' => $this->phone])->one();

        if ($res){
            $this->addError('phone','This phone has already been taken');
            Yii::$app->response->setStatusCode(500);
        }
    }

    /**
     * @return array|User
     * @throws \yii\base\Exception
     */
    public function create()
    {
        if (!$this->validate()) {
            Yii::$app->response->statusCode = 422;
           return $this->getErrors();
        }

        $this->_user = new User();
        $this->_user->setAttributes($this->attributes, '');
        $this->_user->setPassword($this->password);
        $this->_user->generateAuthKey();
        if (!($this->_user->save())) {
            throw new \DomainException("User is not created");
        }

        if (!$this->_createToken()) {
            throw new \DomainException("Token is not created");
        }

        if (!$this->_createRole()) {
            throw  new \DomainException("Role is not created");
        }

        return $this->_user;
    }


    private function _createToken()
    {
        $token = new UserTokens();
        $token->user_id = $this->_user->id;
        $token->token = Yii::$app->security->generateRandomString(32);
        $token->expires = time() + UserTokens::EXPIRE_TIME;
        $token->status = UserTokens::STATUS_ACTIVE;
        return $token->save();
    }

    /**
     * @return \yii\rbac\Assignment
     * @throws \Exception
     */
    private function _createRole()
    {
        $role_name = $this->role;
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole($role_name);
        $auth->revokeAll($this->_user->id);
        return $auth->assign($authorRole, $this->_user->id);
    }

}
