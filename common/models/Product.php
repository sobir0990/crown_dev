<?php

namespace common\models;

use jakharbek\filemanager\models\Files;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property double $price
 * @property double $selling_price
 * @property int $count
 * @property string $files
 * @property int $coming_outgo
 * @property int $coming_data
 * @property int $outgo_data
 * @property int $from_user_id
 * @property int $user_id
 * @property int $models_id
 * @property int $models_category_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 * @property int $order_id
 *
 * @property OrderProducts[] $orderProducts
 * @property Categories $modelsCategory
 * @property Models $models
 * @property User $user
 */
class Product extends \yii\db\ActiveRecord
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
     *   property="price",
     *   type="double",
     *   description="Price"
     * )
     */
    /**
     * @OA\Property(
     *   property="selling_price",
     *   type="double",
     *   description="Selling Price"
     * )
     */
    /**
     * @OA\Property(
     *   property="count",
     *   type="integer",
     *   description="Count"
     * )
     */
    /**
     * @OA\Property(
     *   property="from_user_id",
     *   type="integer",
     *   description="From User Id"
     * )
     */
    /**
     * @OA\Property(
     *   property="coming_outgo",
     *   type="integer",
     *   description="Coming Outgo"
     * )
     */
    /**
     * @OA\Property(
     *   property="user_id",
     *   type="integer",
     *   description="User ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="models_id",
     *   type="integer",
     *   description="Models ID"
     * )
     */
    /**
     * @OA\Property(
     *   property="models_category_id",
     *   type="integer",
     *   description="Models Category ID"
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
     * @OA\Property(
     *   property="coming_data",
     *   type="integer",
     *   description="Coming Data"
     * )
     */
    /**
     * @OA\Property(
     *   property="outgo_data",
     *   type="integer",
     *   description="Outgo Data"
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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    const COMING = 0;
    const OUTGO = 1;
    const SALE = 3;

    const STATUS_REJECTED = 0; //Отклонено
    const STATUS_ARMORED = 3; //Бронировано
    const STATUS_PROCESSING = 2; // В обработке
    const STATUS_IMPLOMENTET = 1; //Реализовано

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'selling_price'], 'number'],
            [['count', 'coming_outgo', 'user_id', 'models_id', 'models_category_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['count', 'coming_outgo', 'user_id', 'models_id', 'from_user_id', 'models_category_id', 'coming_data', 'outgo_data', 'created_at', 'updated_at', 'status', 'order_id'], 'integer'],
//            [['models_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['models_category_id' => 'id']],
//            [['models_id'], 'exist', 'skipOnError' => true, 'targetClass' => Models::className(), 'targetAttribute' => ['models_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'selling_price' => 'Selling Price',
            'count' => 'Count',
            'coming_outgo' => 'Coming Outgo',
            'user_id' => 'User ID',
            'models_id' => 'Models ID',
            'models_category_id' => 'Models Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'models_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasOne(Models::className(), ['id' => 'models_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'from_user_id']);
    }

    public function extraFields()
    {
        return [
            'user',
            'userFrom',
            'models',
            'modelsCategory',
            'totalSum',
            'turnover',
            'balance',
            'remainder'
        ];
    }

    public function getTurnover()
    {
        $query = Product::find()
            ->select('SUM(count) as count')
            ->andWhere(['user_id' => $this->user_id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->andWhere(['not', ['status' => Balans::STATUS_REJECTED]])
            ->asArray()
            ->one();
        return $query['count'];
    }

    public function getBalance()
    {
        $coming = Balans::find()
            ->select('SUM(amount) as count')
            ->andWhere(['user_id' => $this->user_id])
            ->andWhere(['income_outgo' => Product::COMING])
            ->andWhere(['not', ['status' => Balans::STATUS_BRON]])
            ->andWhere(['not', ['status' => Balans::STATUS_REJECTED]])
            ->asArray()
            ->one();

        $outgo = Balans::find()
            ->select('SUM(amount) as count')
            ->andWhere(['user_id' => $this->user_id])
            ->andWhere(['not', ['status' => Balans::STATUS_BRON]])
            ->andWhere(['not', ['status' => Balans::STATUS_REJECTED]])
            ->andWhere(['income_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $balance = $coming['count'] - $outgo['count'];

        return $balance;
    }

    public function getRemainder()
    {
        $coming = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $this->user_id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $this->models_id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();

        $outgo = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $this->user_id])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $this->models_id])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $productCount = $coming['count'] - $outgo['count'];
        return $productCount;
    }

    public function getTotalSum()
    {
        $totalSum = $this->price * $this->count;
        return $totalSum;
    }

}
