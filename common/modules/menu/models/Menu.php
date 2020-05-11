<?php

namespace common\modules\menu\models;

use common\components\ModelLangBehavior;
use jakharbek\langs\components\Lang;
use jakharbek\langs\components\ModelBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $status
 * @property int $lang
 * @property int $sort
 * @property int $full_width
 * @property int $main_menu
 * @property string $title
 * @property string $lang_hash
 * @property string $url
 * @property string $icon
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @param $data
     * @param null $parent
     */
    public static function sortTree($data, $parent = null)
    {
        $pos = 0;

        foreach ($data as $item) {
            if ($link = self::findOne($item['id'])) {
                $link->updateAttributes([
                    'parent_id' => $parent,
                    'sort' => $pos++,
                ]);

                if (isset($item['children']))
                    self::sortTree($item['children'], $link->id);
            }
        }
    }

    public function behaviors()
    {
        return array(
            'lang' => [
                'class' => ModelBehavior::className(),
                'fill' => [
                    'parent_id' => '',
                    'status' => '',
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
            [['title'], 'required'],
            [['title', 'url', 'icon'], 'string', 'max' => 255],
            [['parent_id', 'status', 'lang', 'sort', 'full_width', 'main_menu'], 'integer'],
            [['lang_hash'], 'string', 'max' => 60],
            [
                ['parent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => self::class,
                'targetAttribute' => ['parent_id' => 'id']
            ],
        ];
    }

    public function getMainMenu()
    {
        return $this->hasOne(MainMenu::class, ['id' => 'main_menu']);
    }

    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            'child' => function () {
                return Menu::find()->andWhere(['parent_id' => $this->id])->all();
            }
        ]);
    }
//
//    public static function find()
//    {
//        $query = new MenuQuery(get_called_class());
//        return $query->andWhere(['lang' => Lang::getLangId()]);
//    }


}
