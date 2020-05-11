<?php

namespace common\modules\translations\models;


use Yii;
use yii\caching\TagDependency;
use \yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
//Системные
use jakharbek\core\behaviors\DateTimeBehavior;
use jakharbek\core\behaviors\UserBehavior;
//Файлы
use jakharbek\filemanager\behaviors\FileModelBehavior;
use jakharbek\filemanager\models\Files;
//Языки
use jakharbek\langs\models\Langs;
use jakharbek\langs\components\ModelBehavior;
//Пользовтели
use jakharbek\users\models\Users;
//Категории
use jakharbek\categories\behaviors\CategoryModelBehavior;
use jakharbek\categories\models\Categories;
//Теги
use jakharbek\tags\behaviors\TagsModelBehavior;
use jakharbek\tags\models\Tags;
//Темы
use jakharbek\topics\behaviors\TopicModelBehavior;
use jakharbek\topics\models\Topics;
//Видео
use common\modules\videos\models\Videos;
/**
 * This is the model class for table "source_message".
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends \yii\db\ActiveRecord
{
    const SCENARIO_SEARCH = "search";
    const STATUS_ACTIVE = 1;

    const CACHE_TAG = 'source_message';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '_system_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'message' => 'Message',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])->inverseOf('id0');
    }

    /**
     * @inheritdoc
     * @return SourceMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SourceMessageQuery(get_called_class());
    }


    public static function create($message,$category = "main"){
        $sm = new self();
        $sm->category = $category;
        $sm->message = $message;

        if (static::findOne(['category' => $category, 'message' => $message]) instanceof SourceMessage) return true;

        $sm->validate();
        if($sm->hasErrors()){
            return $sm->getErrors();
        }
        return $sm->save();
    }

}
