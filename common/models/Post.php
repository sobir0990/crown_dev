<?php

namespace common\models;

use jakharbek\filemanager\models\Files;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $files
 * @property string $content
 * @property int $type
 * @property int $view
 * @property int $publish_time
 * @property int $status
 * @property int $top
 * @property int $created_at
 * @property int $updated_at
 */

class Post extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    const TYPE_POST = 1;
    const TYPE_EVENTS = 2;
    const TYPE_SLIDER = 3;
    const TYPE_WORK_PLAN = 4;


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title'
            ],
            'publish_time' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'publish_time',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'publish_time',
                ],
                'value' => function () {
                    return strtotime($this->publish_time);
                },
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'content'], 'string'],
            [['view'], 'safe'],
            [['slug'], 'unique'],
            [['type', 'view', 'status', 'top', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 10],
            [['type', 'view', 'status', 'top', 'publish_time', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'title', 'files'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'description' => 'Description',
            'files' => 'Files',
            'content' => 'Content',
            'type' => 'Type',
            'view' => 'View',
            'status' => 'Status',
            'top' => 'Top',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'lang' => 'Lang',
            'lang_hash' => 'Lang Hash',
        ];
    }

    public function extraFields()
    {
        return [
            'files' => function () {
                if (!empty($this->files)) {
                    return $this->getFiles()->all();
                }
            }
        ];
    }


    public function getFiles()
    {
        return Files::find()->andWhere(['id' => explode(',', $this->files)]);
    }


    /**
     * @return string
     */
    public function getSingleLink()
    {
        return \yii\helpers\Url::to(['/posts/default/view', 'slug' => $this->slug], true);
    }

    /**
     * @return string
     */
    public function getShareLink()
    {
        return "http://yoshlartv.uz/posts/id/" . $this->id;
    }


}
