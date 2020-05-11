<?php


namespace common\modules\story\forms;


use common\models\User;
use Yii;
use yii\base\Model;

class UpdateStoryForms extends Model
{
    public $id;
    public $username;
    public $password;
    public $address;
    public $parent_id;
    public $files;
    public $name;
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
    public $role;

    public $region_id;
    public $longitude;
    public $latitude;

    /**
     * @return User
     */
    private $_user;

    public function rules()
    {
        return [
            [['username', 'name', 'files', 'address', 'longitude', 'latitude', 'phone'], 'string', 'max' => 254],
            [['parent_id', 'is_store', 'is_main', 'region_id', 'created_at', 'updated_at'], 'integer'],
            [['pc', 'mfo', 'inn', 'oked'], 'string', 'max' => 24],
            [['is_main'], 'default', 'value' => 0],
            [['password', 'email', 'bank', 'role'], 'string', 'max' => 255],
            [['status'], 'integer'],
        ];
    }

    public function init()
    {
        $this->_user = User::findOne($this->id);
        $this->setAttributes($this->_user->attributes);
    }

    /**
     * @return array|bool|User|null
     * @throws \yii\base\Exception
     */
    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_STORE);

        $this->_user = User::findOne($this->id);
        $this->_user->setAttributes($this->attributes, '');
        if ($this->password !== null && !empty($this->password)) {
            $this->_user->setPassword($this->password);
            $this->_user->generateAuthKey();
        }

        if ($this->_user->save()) {
            return $this->_user->errors;
        }
        return $this->_user;
    }


}
