<?php


namespace common\modules\story\forms;


use common\models\User;
use Yii;
use yii\base\Model;

class CreateStoreForms extends Model
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
    public $pc;
    public $bank;
    public $mfo;
    public $inn;
    public $is_store;
    public $is_main;
    public $oked;
    public $created_at;
    public $updated_at;
    public $region_id;
    public $longitude;
    public $latitude;
    public $role;

    /**
     * @return User
     */
    private $_user;


    public function rules()
    {
        return [
            [['username', 'name', 'files', 'address', 'longitude', 'latitude', 'phone'], 'string', 'max' => 254],
            [['parent_id','is_store', 'is_main', 'region_id', 'created_at', 'updated_at'], 'integer'],
            [['pc', 'mfo', 'inn', 'oked'], 'string', 'max' => 24],
            ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This phone has already been taken'],
//            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email has already been taken'],
            [['password', 'email', 'bank', 'role'], 'string', 'max' => 255],
            [['status'], 'integer'],
        ];
    }

    /**
     * @return array|User
     * @throws \yii\base\Exception
     */
    public function create()
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        $this->_user = new User();
        $this->_user->setAttributes($this->attributes, '');
        $this->_user->setPassword($this->password);
        $this->_user->generateAuthKey();
        if (!($this->_user->save())) {
            throw new \DomainException("User is not created");
        }

        if (!$this->_createRole()) {
            throw  new \DomainException("Role is not created");
        }

        return $this->_user;
    }

    /**
     * @return \yii\rbac\Assignment
     * @throws \Exception
     */
    private function _createRole()
    {
        $role_name = 'story';
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole($role_name);
        $auth->revokeAll($this->_user->id);
        return $auth->assign($authorRole, $this->_user->id);
    }

}

