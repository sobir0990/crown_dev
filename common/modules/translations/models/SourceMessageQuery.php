<?php

namespace common\modules\translations\models;

use jakharbek\langs\components\QueryBehavior;
use Yii;
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
 * This is the ActiveQuery class for [[SourceMessage]].
 *
 * @see SourceMessage
 */
class SourceMessageQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            'lang' => [
                'class' => QueryBehavior::className(),
                'alias' => SourceMessage::tableName()
            ],
        ];
    }
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SourceMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SourceMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function statuses($status = null){
        if($status == SourceMessageQuery::STATUS_ACTIVE):
            return Yii::t('jakhar-posts','Active');
        else:
            return Yii::t('jakhar-posts','Deactive');
        endif;
    }
}
