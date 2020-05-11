<?php

namespace common\models;

use jakharbek\langs\components\ModelBehavior;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $content
 * @property string $files
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */

/**
 * @OA\Schema(
 *     description=""
 * )
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     *   description="ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="title",
     *   type="string",
     *   description="Title"
     * )
     */
    /**
     * @OA\Property(
     *   property="slug",
     *   type="string",
     *   description="Slug"
     * )
     */
    /**
     * @OA\Property(
     *   property="description",
     *   type="string",
     *   description="Description"
     * )
     */
    /**
     * @OA\Property(
     *   property="content",
     *   type="string",
     *   description="Content"
     * )
     */
    /**
     * @OA\Property(
     *   property="files",
     *   type="string",
     *   description="Files"
     * )
     */
    /**
     * @OA\Property(
     *   property="status",
     *   type="integer",
     *   description="Status"
     * )
     */
    /**
     * @OA\Property(
     *   property="created_at",
     *   type="integer",
     *   description="Created At"
     * )
     */
    /**
     * @OA\Property(
     *   property="updated_at",
     *   type="integer",
     *   description="Updated At"
     * )
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pages';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title'
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['slug'], 'unique'],
            [['status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'slug', 'description', 'files'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'content' => 'Content',
            'files' => 'Files',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'files' => function () {
                if (!empty($this->files)) {
                    return $this->getFiles()->all();
                }
            },
            'icon' => function () {
                if (!empty($this->icon)) {
                    return $this->getIcon()->all();
                }
            },
        ]);
    }

    public function getFiles()
    {
        return \jakharbek\filemanager\models\Files::find()->andWhere(['id' => explode(',', $this->files)]);
    }

    public function getIcon()
    {
        return \jakharbek\filemanager\models\Files::find()->andWhere(['id' => explode(',', $this->icon)]);
    }


}
