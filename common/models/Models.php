<?php

namespace common\models;

use common\modules\settings\models\Settings;
use jakharbek\filemanager\models\Files;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "models".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $files
 * @property string $images
 * @property string $model
 * @property string $performance
 * @property int $top
 * @property int $ball
 * @property int $recent
 * @property int $status
 * @property string $guarantee
 * @property double $price
 * @property int $category_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $count_in_store
 *
 * @property Categories $category
 * @property OrderProducts[] $orderProducts
 * @property Product[] $products
 */
class Models extends \yii\db\ActiveRecord
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
     *   property="description",
     *   type="string",
     *   description="Description"
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
     *   property="price",
     *   type="double",
     *   description="Price"
     * )
     */
    /**
     * @OA\Property(
     *   property="category_id",
     *   type="integer",
     *   description="Category ID"
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
        return 'models';
    }

    /**
     * @param $store_id
     * @param $category_id
     * @return array
     */
    public static function getModelsByStoreId($store_id = null, $category_id = null)
    {
        if ($store_id == null) {
            $store_id = Yii::$app->request->getQueryParam('filter')['store_id'];
        }

        if ($category_id == null) {
            $category_id = Yii::$app->request->getQueryParam('filter')['category_id'];
        }
        if ($category_id !== null) {
            $models = self::find()
                ->andWhere(['category_id' => $category_id])
                ->rightJoin('product', '"models".id = "product".models_id')
                ->andWhere(['"product".user_id' => $store_id])
                ->all();
        } elseif ($category_id == null) {
            $models = self::find()
                ->rightJoin('product', '"models".id = "product".models_id')
                ->andWhere(['"product".user_id' => $store_id])
                ->all();
        }


        $data = [];
        foreach ($models as $model) {
            $count = $model->getCount($store_id);
//            if ($count > 0) {
            $model->count_in_store = $count;
            $data[] = $model;
//            }
        }
        return $data;
    }

    public static function getModelsByUserId($user_id = null, $name = null)
    {
        if ($user_id == null) {
            $user_id = Yii::$app->request->getQueryParam('filter')['user_id'];
        }

        if ($name == null) {
            $name = Yii::$app->request->getQueryParam('filter')['name'];
        }


        $data = [];
        $models = self::find()
            ->rightJoin('product', '"models".id = "product".models_id')
            ->andWhere(['"product".user_id' => $user_id])
            ->andWhere(['ILIKE', 'name', (string)$name])
            ->andWhere(['"product".status' => Product::STATUS_IMPLOMENTET])
            ->all();
        foreach ($models as $model) {
            $count = $model->getCountValidate($user_id);
            if ($count > 0) {
                $model->count_in_store = $count;
                $data[] = $model;
            }
        }
        return $data;

    }


    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['price', 'course'], 'number'],
            [['category_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['top', 'recent', 'slider'], 'default', 'value' => 0],
            [['category_id', 'top', 'recent', 'ball', 'slider', 'status', 'created_at', 'updated_at', 'count_in_store'], 'integer'],
            [['files', 'performance', 'model', 'guarantee', 'name', 'images'], 'string', 'max' => 254],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'description' => 'Description',
            'price' => 'Price',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProducts::className(), ['product_models_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['models_id' => 'id']);
    }

    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            'files' => function () {
                if (!empty($this->files)) {
                    return $this->getFiles()->all();
                }
            },
            'images' => function () {
                if (!empty($this->images)) {
                    return $this->getImages()->all();
                }
            },
            'selling_price_course' => function () {
                return $this->getSellingPrice();
            },
            'guarantee',
            'slider',
            'count'
        ]);
    }

    public function extraFields()
    {
        return [
            'category',
            "orderProducts",
            "products",
            "remainder",
            "market",
            "remains",
            'storeCount' => function () {
                return $this->getStoreCount();
            },
            'bron'
        ];
    }

    public function getStoreCount()
    {
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_MARKET);
        $product = Product::find()
            ->andWhere(['models_id' => $this->id])
            ->leftJoin('user', '"product".user_id = "user".id')
            ->andWhere(['"user".id' => $ids]);

        $coming = $product->select('SUM(count) as count')
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = $product->select('SUM(count) as count')
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();


        return $coming['count'] - $outgo['count'];
    }

    public function getBron($store_id = null)
    {
        if ($store_id == null) {
            $store_id = Yii::$app->request->getQueryParam('filter')['store_id'];
        }

        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_STORE);
        $product = Product::find()
            ->select('SUM(count) as count')
            ->andWhere(['user_id' => $store_id])
//            ->andWhere(['id' => $ids])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['models_category_id' => $this->category_id])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->andWhere(['status' => Order::STATUS_ARMORED])
            ->asArray()
            ->one();

        return $product['count'];

    }

    public function getSellingPrice()
    {
        $model = \common\models\Settings::find()->asArray()->limit(1)->all();
        $selling_price = $model[0]['course'] * $this->price;
        return $selling_price;
    }

    public function getRemainder($store_id = null)
    {

        if ($store_id == null) {
            $store_id = Yii::$app->request->getQueryParam('filter')['store_id'];
        }

        $coming = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $store_id])
            ->andWhere(['status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $store_id])
            ->andWhere(['status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $productCount = $coming['count'] - $outgo['count'];

        return $productCount;
    }

    /**
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function getRemains()
    {
        $user = User::getByToken();

        $coming = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $productCount = $coming['count'] - $outgo['count'];

        return $productCount;
    }

    public function getValidate($user)
    {
        $coming = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $user->id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();
        $productCount = $coming['count'] - $outgo['count'];
        return $productCount;
    }

    public function getCount($store_id = null)
    {
        if ($store_id == null) {
            $store_id = Yii::$app->request->getQueryParam('filter')['store_id'];
        }

        $coming = Product::find()
            ->select('SUM(count) as count')
            ->andWhere(['user_id' => $store_id])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = Product::find()
            ->select('SUM(count) as count')
            ->andWhere(['user_id' => $store_id])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();
        $count = $coming['count'] - $outgo['count'];
        return $count;
    }

    public function getCountValidate($user_id = null)
    {
        if ($user_id == null) {
            $user_id = Yii::$app->request->getQueryParam('filter')['user_id'];
        }

        $coming = Product::find()
            ->select('SUM(count) as count')
            ->andWhere(['user_id' => $user_id])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = Product::find()
            ->select('SUM(count) as count')
            ->andWhere(['user_id' => $user_id])
            ->andWhere(['models_id' => $this->id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();
        $count = $coming['count'] - $outgo['count'];
        return $count;
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     * @throws \yii\web\NotFoundHttpException
     */
    public function getMarket()
    {
        $user = User::getByToken();
        $product = Product::find()
            ->select('user_id, created_at')
            ->andWhere(['from_user_id' => $user->id])
            ->andWhere(['models_id' => $this->id])
            ->one();

        return $product;
    }

    public function getFiles()
    {
        return Files::find()->andWhere(['id' => explode(',', $this->files)]);
    }

    public function getImages()
    {
        return Files::find()->andWhere(['id' => explode(',', $this->images)]);
    }

}
