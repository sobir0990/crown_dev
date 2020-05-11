<?php

namespace common\models;

use jakharbek\filemanager\models\Files;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $type
 * @property int $parent_id
 * @property int $status
 * @property int $show
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Models[] $models
 * @property OrderProducts[] $orderProducts
 * @property Product[] $products
 */
class Categories extends \yii\db\ActiveRecord
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
     *   property="name",
     *   type="string",
     *   description="Name"
     * )
     */
    /**
     * @OA\Property(
     *   property="image",
     *   type="string",
     *   description="Image"
     * )
     */
    /**
     * @OA\Property(
     *   property="type",
     *   type="string",
     *   description="Type"
     * )
     */
    /**
     * @OA\Property(
     *   property="parent_id",
     *   type="integer",
     *   description="Parent ID"
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


    const SHOW = 1;

    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'created_at', 'updated_at', 'show'], 'default', 'value' => null],
            [['parent_id', 'status', 'created_at', 'updated_at', 'show'], 'integer'],
            [['name', 'type'], 'string', 'max' => 254],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'type' => 'Type',
            'parent_id' => 'Parent ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function getModels()
    {
        $modelName = \Yii::$app->request->getQueryParam('filter')['modelName'];
        $requestParams = $this->RequestParams();
        $user = User::getByToken();
        $models = Models::find()
            ->rightJoin('product', '"models".id = "product".models_id')
            ->andWhere(['models.category_id' => $this->id])
            ->andWhere(['"product".status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['"product".user_id' => $user->id]);
        if ($requestParams['filter']['price'] !== null) {
            if ($requestParams['filter']['price'] == 1) {
                $models->orderBy(['"models".price' => SORT_ASC]);
            } elseif ($requestParams['filter']['price'] == 2) {
                $models->orderBy(['"models".price' => SORT_DESC]);
            }
        }
        if ($modelName !== null) {
            $models->andWhere(['ILIKE', '"models".name', (string)$modelName]);
        }

        $data = [];
        foreach ($models->all() as $model) {
            /**
             * @var $model Models
             */
            if ($model->getValidate($user) > 0) {
                $data[] = $model;
            }
        }
        return $data;
    }

    public function getMarket()
    {
        $store_id = \Yii::$app->request->getQueryParam('filter')['store_id'];
        $models = Models::find()
            ->andWhere(['category_id' => $this->id])
            ->rightJoin('product', '"models".id = "product".models_id')
            ->andWhere(['"product".status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['"product".user_id' => $store_id])
            ->all();
        $data = [];
        foreach ($models as $model) {
            /**
             * @var $model Models
             */
            if ($model->getCount($store_id) > 0) {
                $data[] = $model;
            }
        }
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProducts::className(), ['product_models_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['models_category_id' => 'id']);
    }

    public function getStore()
    {
        $store_id = \Yii::$app->request->getQueryParam('filter')['store_id'];
        $modelName = \Yii::$app->request->getQueryParam('filter')['modelName'];

        $models = Models::find()
            ->andWhere(['category_id' => $this->id])
//            ->andWhere(['not', ['coming_outgo' => Product::OUTGO]])
            ->rightJoin('product', '"models".id = "product".models_id')
            ->andWhere(['ILIKE', '"models".name', (string)$modelName])
            ->andWhere(['"product".user_id' => $store_id])
            ->all();

        $data = [];
        foreach ($models as $model) {
            /**
             * @var $model Models
             */
            if ($model->getCount($store_id) > 0) {
                $data[] = $model;
            }
        }
        return $data;
    }

    public function extraFields()
    {
        return [
            'models' => function () {
                return $this->getModels();
            },
            'market' => function () {
                return $this->getMarket();
            },
            'orderProducts' => function () {
                return $this->getOrderProducts()->all();
            },
            'products' => function () {
                return $this->getProducts()->all();
            },
            'store' => function () {
                return $this->getStore();
            }
        ];
    }


    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            'image' => function () {
                if (!empty($this->image)) {
                    return $this->getImage()->all();
                }
            },
        ]);
    }

    public function getImage()
    {
        return Files::find()->andWhere(['id' => explode(',', $this->image)]);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function RequestParams()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        return $requestParams;
    }

}
