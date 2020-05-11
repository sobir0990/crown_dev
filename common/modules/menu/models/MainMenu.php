<?php

namespace common\modules\menu\models;

use jakharbek\langs\components\Lang;
use jakharbek\langs\components\ModelBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "main_menu".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $status
 * @property string $lang_hash
 * @property int $lang
 *
 * @property Menu[] $menus
 */
class MainMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'main_menu';
    }

    public function behaviors()
    {
        return array(
            'lang' => [
                'class' => ModelBehavior::className(),
                'fill' => [
                    'status' => '',
                    'slug' => ''
                ],
            ],

        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'lang'], 'default', 'value' => null],
            [['status', 'lang'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['lang_hash'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'status' => 'Status',
            'lang_hash' => 'Lang Hash',
            'lang' => 'Lang',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['main_menu' => 'id'])->onCondition(['parent_id' => null])->orderBy(['sort' => SORT_ASC]);
    }

    public function extraFields()
    {
        return array(
            'menus'
        );
    }

}
