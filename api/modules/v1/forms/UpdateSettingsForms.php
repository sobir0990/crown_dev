<?php


namespace api\modules\v1\forms;


use common\models\Settings;
use yii\base\Model;

class UpdateSettingsForms extends Model
{

    public $id;
    public $email;
    public $phone;
    public $address;
    public $course;
    public $longitude;
    public $latitude;
    public $android;
    public $ios;

    public function rules()
    {
        return [
            [['email', 'phone', 'address', 'course',
              'longitude', 'latitude', 'android', 'ios'], 'string', 'max' => 254],
            [['course'], 'safe']

        ];
    }

    public function update()
    {
        $model = Settings::findOne($this->id);
        $model->setAttributes($this->attributes, '');
        $model->save();
        return $model;
    }
}