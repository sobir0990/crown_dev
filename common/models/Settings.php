<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "reference".
 *
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $android
 * @property string $ios
 * @property string $longitude
 * @property string $latitude
 * @property string $course
 */

class Settings extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'address', 'longitude', 'latitude', 'android', 'ios'], 'string', 'max' => 254],
            [['course'], 'string']
        ];
    }

    public function fields()
        {
            return ArrayHelper::merge(parent::fields(), [
                'is_fill' => function(){
                return $this->getIsFill();
                },
                'android',
                'ios',
            ]);
        }


    public function getIsFill(){
        $model = Settings::find()->one();
        if ($model){
            return true;
        }else{
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'email' => 'Email',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
        ];
    }


}
