<?php

namespace common\models;

use common\modules\user\repositories\UserRepository;
use jakharbek\filemanager\models\Files;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $phone
 * @property int $pc
 * @property int $parent_id
 * @property int $district_id
 * @property string $bank
 * @property string $address
 * @property string $is_store
 * @property string $is_main
 * @property string $files
 * @property string $name
 * @property int $mfo
 * @property int $inn
 * @property int $oked
 * @property string $email
 * @property string $ball
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $region_id
 *
 * @property Balans[] $balans
 * @property Order[] $orders
 * @property OrderProducts[] $orderProducts
 * @property Product[] $products
 * @property Region $region
 */
class User extends ActiveRecord implements IdentityInterface
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
     *   property="username",
     *   type="string",
     *   description="Username"
     * )
     */
    /**
     * @OA\Property(
     *   property="phone",
     *   type="string",
     *   description="Phone"
     * )
     */
    /**
     * @OA\Property(
     *   property="address",
     *   type="string",
     *   description="Address"
     * )
     */
    /**
     * @OA\Property(
     *   property="pc",
     *   type="integer",
     *   description="Pc"
     * )
     */
    /**
     * @OA\Property(
     *   property="is_store",
     *   type="integer",
     *   description="Store"
     * )
     */
    /**
     * @OA\Property(
     *   property="is_main",
     *   type="integer",
     *   description="Is Main"
     * )
     */
    /**
     * @OA\Property(
     *   property="parent_id",
     *   type="integer",
     *   description="Parent Id"
     * )
     */
    /**
     * @OA\Property(
     *   property="bank",
     *   type="string",
     *   description="Bank"
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
     *   property="mfo",
     *   type="integer",
     *   description="Mfo"
     * )
     */
    /**
     * @OA\Property(
     *   property="inn",
     *   type="integer",
     *   description="Inn"
     * )
     */
    /**
     * @OA\Property(
     *   property="oked",
     *   type="integer",
     *   description="Oked"
     * )
     */
    /**
     * @OA\Property(
     *   property="email",
     *   type="string",
     *   description="Email"
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
     *   property="auth_key",
     *   type="string",
     *   description="Auth Key"
     * )
     */
    /**
     * @OA\Property(
     *   property="password_hash",
     *   type="string",
     *   description="Password Hash"
     * )
     */
    /**
     * @OA\Property(
     *   property="password_reset_token",
     *   type="string",
     *   description="Password Reset Token"
     * )
     */
    /**
     * @OA\Property(
     *   property="verification_token",
     *   type="string",
     *   description="Verification Token"
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
     * @OA\Property(
     *   property="region_id",
     *   type="integer",
     *   description="Region ID"
     * )
     */

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 9;

    const ROLE_ADMIN = "admin";
    const ROLE_COMPANY = "company";
    const ROLE_STORE = "story";
    const ROLE_DILLER = "diller";
    const ROLE_MARKET = "market";
    const ROLE_CLIENT = "client";

    const IS_MAIN = 10; //главний компания
    const IS_NO_MAIN = 9;

    const STORE_ACTIVE = 10; //склад
    const NO_STORE = 9;

    const CURRENT = 1;
    const NO_CURRENT = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'phone', 'name', 'address', 'ball', 'working_hours', 'files', 'email'], 'string', 'max' => 254],
            [['is_store', 'is_main', 'parent_id', 'created_at', 'updated_at', 'district_id'], 'integer'],
            [['pc', 'mfo', 'inn', 'oked'], 'string', 'max' => 24],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public function fields()
    {
        return [
            'id',
            'username',
            'name',
            'phone',
            'pc',
            'bank',
            'address',
            'mfo',
            'inn',
            'oked',
            'parent_id',
            'is_store',
            'is_main',
            'email',
            'ball',
            'longitude',
            'latitude',
            'working_hours',
            'status',
            'files' => function () {
                if (!empty($this->files)) {
                    return $this->getFiles()->all();
                }
            },
            'created_at',
            'updated_at',
            'region_id',
            'role',
            'token' => function () {
                return $this->getUserTokens()->one();
            },
        ];
    }

    public function getRole()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRolesByUser($this->id);
        return array_pop($role);
    }


    public function getUserTokens()
    {
        return $this->hasMany(UserTokens::class, ['user_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['user_id' => 'id']);
    }

    public function extraFields()
    {
        return [
            'turnover',
            'remainder',
            'balance',
            'marketBalance',
            'models',
            'market',
            'clients',
            'role',
            'region' => function () {
                return $this->getRegion()->one();
            },
            'district' => function () {
                return $this->getDistrict()->one();
            },
            'category',
            'product' => function () {
                return $this->getProduct()->all();
            },
            'marketCount',
            'parent' => function () {
                return $this->getParent()->all();
            },
            'count' => function () {
                return $this->getCountUser();
            }
        ];
    }


    public function getTurnover()
    {
        $query = Product::find()
            ->select('SUM(count) as count')
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->asArray()
            ->one();
        return $query['count'];
    }

    public function getCategory()
    {
        $category = (new yii\db\Query())
            ->select(['models_category_id'])
            ->from('product')
            ->andWhere(['user_id' => $this->id])
            ->distinct()
            ->count();

        return $category;
    }

    public function getRemainder()
    {
        $coming = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();


        $outgo = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['status' => Product::STATUS_IMPLOMENTET])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $product = $coming['count'] - $outgo['count'];

        return $product;
    }

    public function getCountUser()
    {
        $product = Product::find()
            ->select('COUNT(count) as count')
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['status' => Product::STATUS_IMPLOMENTET])
            ->asArray()
            ->one();
        return $product;
    }


    public function getBalance()
    {
        $coming = Balans::find()
            ->select('SUM(amount) as count')
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['income_outgo' => Product::COMING])
            ->andWhere(['status' => Balans::STATUS_APPROVED])
            ->asArray()
            ->one();


        $outgo = Balans::find()
            ->select('SUM(amount) as count')
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['status' => Balans::STATUS_APPROVED])
            ->andWhere(['income_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $balance = $coming['count'] - $outgo['count'];

        return $balance;
    }

    public function getMarketBalance()
    {
        $store_id = \Yii::$app->request->getQueryParam('filter')['store_id'];
        $coming = Balans::find()
            ->select('SUM(amount) as count')
            ->andWhere(['user_id' => $store_id])
            ->andWhere(['income_outgo' => Product::COMING])
            ->andWhere(['status' => Balans::STATUS_APPROVED])
            ->asArray()
            ->one();

        $outgo = Balans::find()
            ->select('SUM(amount) as count')
            ->andWhere(['user_id' => $store_id])
            ->andWhere(['status' => Balans::STATUS_APPROVED])
            ->andWhere(['income_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $balance = $coming['count'] - $outgo['count'];

        return $balance;
    }

    public function getModels()
    {
        $model = Product::find()
            ->andWhere(['user_id' => $this->id])
            ->all();
        return $model;
    }

    public function getMarketCount()
    {
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_MARKET);
        $user = User::find()
            ->andWhere(['id' => $ids])
            ->andWhere(['parent_id' => $this->id])
            ->count();
        return $user;
    }


    public function getRegion()
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);

    }

    public function getDistrict()
    {
        return $this->hasOne(District::class, ['id' => 'district_id']);

    }

    public function getMarket()
    {
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_MARKET);
        $market = User::find()
            ->andWhere(['id' => $ids])
            ->andWhere(['parent_id' => $this->id])
            ->all();

        return $market;
    }

    public function getParent()
    {
        return $this->hasOne(User::class, ['id' => 'parent_id']);
    }

    public function getClients()
    {
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_CLIENT);
        $clients = User::find()
            ->andWhere(['id' => $ids])
            ->andWhere(['parent_id' => $this->id])
            ->all();

        return $clients;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /**
         * @var $userRepository UserRepository
         */
        $userRepository = Yii::$container->get(UserRepository::class);
        return $userRepository->getUserByValidToken($token);
    }

    public function setActivated()
    {
        return $this->updateAttributes([
            'status' => static::STATUS_ACTIVE
        ]);
    }

    public function getFiles()
    {
        return Files::find()->andWhere(['id' => explode(',', $this->files)]);
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return User|null
     * @throws NotFoundHttpException
     */
    public static function authorize()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (is_object($user)) {
            return $user;
        }
        throw new NotFoundHttpException('User is not found');
    }

    /**
     * @return bool|User|IdentityInterface|null
     * @throws NotFoundHttpException
     */
    public static function getByToken()
    {
        if ($auth = Yii::$app->request->headers->get('Authorization')) {
            $token = str_replace('Bearer ', '', $auth);
            if ($user = static::findIdentityByAccessToken($token))
                return $user;
            else throw new NotFoundHttpException('User not found');
        }
        return false;
    }
}
