<?php

namespace common\modules\user\forms;

use common\models\User;
use common\modules\user\repositories\UserRepository;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class UpdateUserForm
 * @package common\modules\user\forms
 */
class UpdateUserForm extends Model
{
    public $id;
    public $username;
    public $password;
    public $address;
    public $email;
    public $name;
    public $phone;
    public $ball;
    public $files;
    public $status;
    public $parent_id;
    public $region_id;
    public $pc;
    public $bank;
    public $is_store;
    public $is_main;
    public $mfo;
    public $inn;
    public $oked;
    public $created_at;
    public $updated_at;

    public $longitude;
    public $latitude;

    /**
     * @var User
     */
    public $user;



    public function init()
    {
        $this->user = User::findOne($this->id);
        $this->setAttributes($this->user->attributes);
    }
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'name', 'files', 'ball', 'address', 'phone'], 'string', 'max' => 254],
            [['parent_id','is_store', 'is_main', 'region_id', 'created_at', 'updated_at'], 'integer'],
            [['pc', 'mfo', 'inn', 'oked'], 'string', 'max' => 24],
            [['phone'], 'phoneValidator'],
            [['longitude', 'latitude'], 'string', 'max' => 254],
            [['password', 'bank', 'email'], 'string', 'max' => 255],
            [['status'], 'integer'],
        ];
    }

    public function phoneValidator($attributes)
    {
        if (User::find()->andWhere(['phone' => $this->phone])->one()->id == $this->id) {
            return true;
        } else {
            return $this->addError($attributes, 'This phone has already been taken.');
        }
    }

    /**
     * @return bool|User
     * @throws \yii\base\Exception
     */
    public function update()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->user->setAttributes($this->attributes,'');
        if ($this->password !== null && !empty($this->password)) {
            $this->user->setPassword($this->password);
            $this->user->generateAuthKey();
        }
        if (!($this->user->save())) {
            return false;
        }
        return $this->user;
    }
}
