<?php

namespace jakharbek\filemanager\models;

use jakharbek\filemanager\helpers\FileManagerHelper;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $slug
 * @property string $name
 * @property string $ext
 * @property string $file
 * @property string $folder
 * @property string $path
 * @property string $domain
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 * @property int $status
 * @property string $upload_data
 * @property string $params
 * @property int $size
 * @property string $link
 * @property string $linkAbsolute
 */

/**
 * @OA\Schema()
 */
class Files extends \yii\db\ActiveRecord
{

    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     *   description="ID",
     *   readOnly=true
     * )
     */
    /**
     * @OA\Property(
     *   property="title",
     *   type="string",
     *   description="Title",
     *     example="photo-1517846693594-1567da72af75.jpeg"
     * )
     */

    /**
     * @OA\Property(
     *   property="description",
     *   type="string",
     *   description="Description",
     *     example="photo-1517846693594-1567da72af75.jpeg"
     * )
     */

    /**
     * @OA\Property(
     *   property="slug",
     *   type="string",
     *   description="Slug",
     *     example="photo-1517846693594-1567da72af75jpeg"
     * )
     */

    /**
     * @OA\Property(
     *   property="name",
     *   type="string",
     *   description="Name",
     *   example="photo-1517846693594-1567da72af75.jpeg"
     * )
     */

    /**
     * @OA\Property(
     *   property="ext",
     *   type="ext",
     *   description="Extension",
     *   example="jpeg"
     * )
     */

    /**
     * @OA\Property(
     *   property="file",
     *   type="string",
     *   description="File",
     *   example="photo-1517846693594-1567da72af75_lvXXVhqyq0"
     * )
     */

    /**
     * @OA\Property(
     *   property="folder",
     *   type="string",
     *   description="Folder",
     *     example="2019/10/01/06/28/"
     * )
     */

    /**
     * @OA\Property(
     *   property="path",
     *   type="string",
     *   description="Path",
     *     example="/app/application/static/2019/10/01/06/28/"
     * )
     */

    /**
     * @OA\Property(
     *   property="domain",
     *   type="string",
     *   description="Domain",
     *     example="http://cdn.zakovat.loc:84/"
     * )
     */

    /**
     * @OA\Property(
     *   property="created_at",
     *   type="string",
     *   description="Created At",
     *   readOnly=true,
     *     example="1569911295"
     * )
     */

    /**
     * @OA\Property(
     *   property="updated_at",
     *   type="string",
     *   description="Updated At",
     *   readOnly=true,
     *     example="1569911295"
     * )
     */

    /**
     * @OA\Property(
     *   property="user_id",
     *   type="integer",
     *   description="User ID",
     *     example="1"
     * )
     */

    /**
     * @OA\Property(
     *   property="status",
     *   type="integer",
     *   description="Status (Active = 2, Inactive = 1, Deleted = 0)",
     *     example="1"
     * )
     */

    /**
     * @OA\Property(
     *   property="size",
     *   type="integer",
     *   description="Size",
     *   readOnly=true,
     *     example="107171"
     * )
     */

    /**
     * @OA\Property(
     *   property="link",
     *   type="string",
     *   description="Link",
     *   readOnly=true,
     *     example="http://cdn.zakovat.loc:84/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0.jpeg"
     * )
     */

    /**
     * @OA\Property(
     *   property="linkAbsolute",
     *   type="string",
     *   description="Link",
     *   readOnly=true,
     *     example="http://cdn.zakovat.loc:84/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0.jpeg"
     * )
     */

       /**
     * @OA\Property(
     *   property="thumbnails",
     *   type="object",
     *   description="Thumbnails only images",
     *   readOnly="true",
     *     example={
       "icon": {
       "w": 50,
       "h": 50,
       "q": 100,
       "slug": "icon",
       "src": "http://cdn.zakovat.loc:84/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_icon.jpeg",
       "path": "/app/application/static/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_icon.jpeg"
       },
       "small": {
       "w": 320,
       "h": 320,
       "q": 100,
       "slug": "small",
       "src": "http://cdn.zakovat.loc:84/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_small.jpeg",
       "path": "/app/application/static/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_small.jpeg"
       },
       "low": {
       "w": 640,
       "h": 640,
       "q": 100,
       "slug": "low",
       "src": "http://cdn.zakovat.loc:84/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_low.jpeg",
       "path": "/app/application/static/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_low.jpeg"
       },
       "normal": {
       "w": 1024,
       "h": 1024,
       "q": 100,
       "slug": "normal",
       "src": "http://cdn.zakovat.loc:84/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_normal.jpeg",
       "path": "/app/application/static/2019/10/01/06/28/photo-1517846693594-1567da72af75_lvXXVhqyq0_normal.jpeg"
       }
       }
     * )
     */


    const STATUS_ACTIVE = 2;
    const STATUS_INACTIVE = 1;
    const STATUS_DELETED = 0;

    /**
     * @return int
     */
    public function setActive()
    {
        return $this->updateAttributes(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * @return int
     */
    public function setInactive()
    {
        return $this->updateAttributes(['status' => self::STATUS_INACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'timestamp' => [
                    'class' => TimestampBehavior::class,
                    'createdAtAttribute' => null
                ],
                'slug' => [
                    'class' => SluggableBehavior::class,
                    'attribute' => 'title',
                    'slugAttribute' => 'slug'
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'name', 'file', 'folder', 'domain', 'upload_data', 'params', 'path'], 'string'],
            [['created_at', 'updated_at', 'user_id', 'status', 'size'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 500],
            [['ext'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('filemanager', 'ID'),
            'title' => Yii::t('filemanager', 'Title'),
            'description' => Yii::t('filemanager', 'Description'),
            'slug' => Yii::t('filemanager', 'Slug'),
            'name' => Yii::t('filemanager', 'Name'),
            'ext' => Yii::t('filemanager', 'Ext'),
            'file' => Yii::t('filemanager', 'File'),
            'folder' => Yii::t('filemanager', 'Folder'),
            'domain' => Yii::t('filemanager', 'Domain'),
            'created_at' => Yii::t('filemanager', 'Created At'),
            'updated_at' => Yii::t('filemanager', 'Updated At'),
            'user_id' => Yii::t('filemanager', 'User ID'),
            'status' => Yii::t('filemanager', 'Status'),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->created_at == null) {
            $this->created_at = time();
        }

        if (!Yii::$app->user->isGuest) {
            $this->user_id = Yii::$app->user->id;
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     * @return FilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FilesQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return FileManagerHelper::getDomain($this->domain) . $this->folder . $this->file . "." . $this->ext;
    }

    /**
     * @return string
     */
    public function getLinkAbsolute()
    {
        return FileManagerHelper::getDomain($this->domain, true) . $this->folder . $this->file . "." . $this->ext;
    }

    /**
     * @return array|false
     */
    public function fields()
    {
        $fields = [
            'link',
            'linkAbsolute'
        ];

        if($this->getIsImage()){
            $fields['thumbnails'] = 'imageThumbs';
        }

        unset($fields['upload_data']);
        return ArrayHelper::merge(parent::fields(), $fields);
    }

    /**
     * @return string
     */
    public function getDist()
    {
        return $this->path . $this->file . "." . $this->ext;
    }

    public function getDistFile()
    {
        return getenv("STATIC_URL").$this->folder.$this->file  . "." . $this->ext;
    }


    /**
     * @return bool
     */
    public function getIsImage()
    {
        return in_array($this->ext, FileManagerHelper::getImagesExt());
    }

    /**
     * @return mixed
     */
    public function getImageThumbs()
    {
        $thumbsImages = FileManagerHelper::getThumbsImage();
        foreach ($thumbsImages as &$thumbsImage) {
            $slug = $thumbsImage['slug'];
            $newFileDist = getenv("STATIC_URL").$this->folder.$this->file . "_".$slug . "." . $this->ext;
            $thumbsImage['src'] = $newFileDist;
            $thumbsImage['path'] = $this->path . $this->file . "_".$slug . "." . $this->ext;

        }
        return $thumbsImages;
    }
}
