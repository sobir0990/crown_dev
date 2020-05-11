<?php


namespace common\modules\user\forms;


use common\models\User;
use Yii;
use yii\base\Model;

class UserUpdateForms extends Model
{

    public $username;
    public $password;
    public $address;
    public $email;
    public $id;
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
    public $district_id;

    public $longitude;
    public $latitude;
    public $working_hours;

    /**
     * @var User
     */
    private $user;


    public function rules()
    {
        return [
            [['username', 'name', 'files', 'ball', 'address', 'phone', 'working_hours'], 'string', 'max' => 254],
            [['parent_id','is_store', 'is_main', 'region_id', 'created_at', 'updated_at', 'district_id'], 'integer'],
            [['pc', 'mfo', 'inn', 'oked'], 'string', 'max' => 24],
            [['phone'], 'phoneValidator'],
            [['longitude', 'latitude'], 'safe'],
//            [['email'], 'emailValidator'],
            [['password', 'bank', 'email'], 'string', 'max' => 255],
            [['status'], 'integer'],
        ];
    }

    public function phoneValidator($attributes)
    {
        if ($this->user->phone == $this->phone) {
            return true;
        }
        if (User::find()->andWhere(['phone' => $this->phone])->exists()){
            Yii::$app->response->setStatusCode(422);
            return $this->addError($attributes,'This phone has already been taken.');
        }
        return true;
    }

    public function init()
    {
        $this->user = User::findOne($this->id);
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
        if (!empty($this->is_main) && $this->is_main == User::CURRENT) {
            $auth = Yii::$app->authManager;
            $ids = $auth->getUserIdsByRole(User::ROLE_COMPANY);
            $user = User::find()->andWhere(['id' => $ids])->andWhere(['is_main' => User::CURRENT])->one();
            $user->updateAttributes(['is_main' => User::NO_CURRENT]);
        }
        if (empty($this->is_main)) {
            $this->is_main = $this->user->is_main;
        }
        $this->user->setAttributes($this->attributes, '');

        if ($this->password !== null && !empty($this->password)) {
            $this->user->setPassword($this->password);
            $this->user->generateAuthKey();
        }
        $this->user->status = $this->status;
        $this->user->longitude = $this->longitude;
        $this->user->latitude = $this->latitude;
        if ($this->user->save()) {
            return $this->user->errors;
        }
        return $this->user;
    }

}
