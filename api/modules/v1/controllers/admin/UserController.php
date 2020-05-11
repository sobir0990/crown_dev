<?php

namespace api\modules\v1\controllers\admin;

use common\models\Product;
use common\models\search\UserSearch;
use common\models\User;
use common\modules\user\forms\ApprovePhoneForm;
use common\modules\user\forms\CreateUserForm;
use common\modules\user\forms\LoginForm;
use common\modules\user\forms\LoginFormAdmin;
use common\modules\user\forms\RegistrationByPhoneForm;
use common\modules\user\forms\UserUpdateForms;
use common\modules\user\repositories\UserRepository;
use common\modules\user\services\UserServices;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\rest\OptionsAction;

class UserController extends Controller
{
//    public function behaviors()
//    {
//        return ArrayHelper::merge(parent::behaviors(), [
//            'access' => [
//                'class' => AccessControl::className(),
//                'except' => ['get-me', 'sign-in-admin'],
//                'denyCallback' => function () {
//                    throw new \DomainException("Access Denied");
//                },
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => [User::ROLE_ADMIN, User::ROLE_COMPANY],
//                    ],
//                ],
//            ]
//        ]);
//    }

    public $modelClass = User::class;
    public $searchModel = UserSearch::class;

    public $serializer = [
        'class' => '\yii\rest\Serializer',
        'collectionEnvelope' => 'data',
        'expandParam' => 'include'
    ];

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        return array(
            'view' => array(
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
            ),
            'options' => array(
                'class' => OptionsAction::class
            ),
        );
    }

    public function actionIndex()
    {
        $query = User::find();
        if (($role = \Yii::$app->request->getQueryParam('filter')['role']) !== null) {

            if (($company_id = \Yii::$app->request->getQueryParam('filter')['company_id']) !== null) {

                if ($role == 'diller') {
                    $auth = Yii::$app->authManager;
                    $ids = $auth->getUserIdsByRole($role);
                    $query->andWhere(['"user".id' => $ids]);
                    $query->andWhere(['parent_id' => $company_id]);
                } elseif ($role == 'market') {
                    $auth = Yii::$app->authManager;
                    $ids = $auth->getUserIdsByRole('diller');
                    $query->andWhere(['"user".id' => $ids]);
                    $query->andWhere(['parent_id' => $company_id]);
                    $ids = ArrayHelper::map($query->asArray()->all(), 'id', 'id');
                    $query = User::find()->andWhere(['user.parent_id' => $ids]);
                } elseif ($role == 'client') {
                    if (($diller = \Yii::$app->request->getQueryParam('filter')['diller']) !== null) {
                        $query->andWhere(['"user".id' => $diller]);
                        $query->andWhere(['parent_id' => $company_id]);
                        $ids = ArrayHelper::map($query->asArray()->all(), 'id', 'id');
                        $query = User::find()->andWhere(['user.parent_id' => $ids]);
                    } else {
                        $auth = Yii::$app->authManager;
                        $ids = $auth->getUserIdsByRole('client');
                        $query->andWhere(['"user".id' => $ids]);
                    }
//                    $query->andWhere(['parent_id' => $company_id]);
//                    $ids = ArrayHelper::map($query->asArray()->all(), 'id', 'id');
//                    $ids = ArrayHelper::map(User::find()->andWhere(['user.parent_id' => $ids])->asArray()->all(), 'id', 'id');
//                    $query = User::find()->andWhere(['user.parent_id' => $ids]);
                } else {
                    $auth = Yii::$app->authManager;
                    $ids = $auth->getUserIdsByRole($role);
                    $query->andWhere(['user.id' => $ids]);
                }

            } elseif ($role == 'company') {
                $auth = Yii::$app->authManager;
                $ids = $auth->getUserIdsByRole($role);
                $query->andWhere(['"user".id' => $ids]);
            }
        }

        if (($parent_id = \Yii::$app->request->getQueryParam('filter')['parent_id']) !== null) {
            $query->andWhere(['parent_id' => $parent_id]);
        }

        if (($roles = Yii::$app->request->getQueryParam('filter')['roles']) !== null) {
            $auth = Yii::$app->authManager;
            $query->andWhere(['"user".id' => $auth->getUserIdsByRole($roles)]);
        }


        if (($roles = Yii::$app->request->getQueryParam('filter')['roles']) !== null) {
            $auth = Yii::$app->authManager;
            $query->andWhere(['"user".id' => $auth->getUserIdsByRole($roles)]);
        }


        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', 'name', (string)$name]);
        }

        if (($phone = Yii::$app->request->getQueryParam('filter')['phone']) !== null) {
            $query->andWhere(['ILIKE', 'phone', (string)$phone]);
        }

        if (($username = Yii::$app->request->getQueryParam('filter')['username']) !== null) {
            $query->andWhere(['ILIKE', 'username', (string)$username]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionDiller()
    {
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_DILLER);

        $query = User::find()->andWhere(['"user".id' => $ids]);

        if (($region_id = \Yii::$app->request->getQueryParam('filter')['region_id']) !== null) {
            $query->andWhere(['region_id' => $region_id]);
        }

        if (($username = Yii::$app->request->getQueryParam('filter')['username']) !== null) {
            $query->andWhere(['ILIKE', 'username', (string)$username]);
        }


        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', 'name', (string)$name]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionMarket()
    {
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_MARKET);

        $query = User::find()->andWhere(['"user".id' => $ids]);

        if (($username = Yii::$app->request->getQueryParam('filter')['username']) !== null) {
            $query->andWhere(['ILIKE', 'username', (string)$username]);
        }

        if (($parent_id = \Yii::$app->request->getQueryParam('filter')['parent_id']) !== null) {
            $query->andWhere(['parent_id' => $parent_id]);
        }

        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', 'name', (string)$name]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionCompany()
    {
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_COMPANY);

        $query = User::find()->andWhere(['"user".id' => $ids]);

        if (($username = Yii::$app->request->getQueryParam('filter')['username']) !== null) {
            $query->andWhere(['ILIKE', 'username', (string)$username]);
        }


        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', 'name', (string)$name]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }


//admin

    /**
     * @return \yii\web\IdentityInterface|null
     */
    public function actionGetMeAdmin()
    {
        return \Yii::$app->user->identity;
    }

    /**
     * @return array|\yii\web\IdentityInterface|null
     */
    public function actionSignInAdmin()
    {
        $model = new LoginFormAdmin();
        if ($model->load(\Yii::$app->request->post(), '') && $user = $model->signin()) {
            return $user;
        }
        \Yii::$app->response->setStatusCode(401);

        return $model->getErrors();
    }


//user create

    /**
     * @return bool|User
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new CreateUserForm();
        $model->load($this->requestParams(), '');
        return $model->create();
    }

    public function RequestParams()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        return $requestParams;
    }

    /**
     * @param $id
     * @return array|bool|UserUpdateForms
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $userForm = new UserUpdateForms(['id' => $id]);
        $userForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($user = $userForm->update()) {
            return $user;
        }
        return $userForm;
    }

    public function actionGetMe()
    {
        /**
         * @var $userRepostiroy UserRepository
         */
        $userRepostiroy = Yii::$container->get(UserRepository::class);
        try {
            $user = $userRepostiroy->getByID(Yii::$app->user->id);
        } catch (\Exception $exception) {
            throw new \DomainException('User is not auth', 401);
        }
        if ($user->status == User::STATUS_ACTIVE) {
            return $user;
        }

        Yii::$app->response->setStatusCode(401, 'User is not auth');
    }

    /**
     * @return array|bool|\common\models\UserTokens
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionSignIn()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $form = new LoginForm();
        $form->setAttributes($requestParams);
        $token = $form->getToken();

        if (!is_object($token)) {
            Yii::$app->response->statusCode = 400;
            Yii::$app->response->statusText = "Bad Request";
            return $form->getErrors();
        }

        return $token;
    }

    /**
     * @return array|RegistrationByPhoneForm
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionSignUp()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        $form = new RegistrationByPhoneForm();
        $form->setAttributes($requestParams);

        if (!$form->save()) {
            Yii::$app->response->statusCode = 400;
            Yii::$app->response->statusText = "Bad Request";
            return $form->getErrors();
        }

        return $form;
    }

    /**
     * @param $phone
     * @return array|bool|\common\models\UserTokens
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionApprovePhone($phone)
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $form = new ApprovePhoneForm();
        $form->setAttributes($requestParams);
        $form->phone = $phone;
        if (!($token = $form->approve())) {
            Yii::$app->response->statusCode = 400;
            Yii::$app->response->statusText = "Bad Request";
            return $form->getErrors();
        }

        return $token;
    }

    /**
     * @param $phone
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionResendApproveCode($phone)
    {
        /**
         * @var $service UserServices
         */
        $service = Yii::$container->get(UserServices::class);
        $service->sendRequestPhoneApprove($phone);
        Yii::$app->response->statusText = "Code is resent";
    }

    /**
     * @api {get} /user/logout Logging out user
     * @apiGroup User
     * @apiName Logout
     * @apiDescription Logs out current user from site
     * @apiVersion 1.0.0
     * @apiHeader {string} Authorization='Bearer <token>' authorization token.
     *
     * @apiSuccess NULL nothing will return
     */

    /**
     * @param $user_id
     * @return User
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRole($user_id)
    {
        $requestParams = Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->request->getQueryParams();
        }
        /**
         * @var $userRepository UserRepository
         */
        $userRepository = Yii::$container->get(UserRepository::class);
        $user = $userRepository->getByID($user_id);
        $auth = Yii::$app->authManager;
        $role = $auth->getRolesByUser($user->id);
        if (!key_exists($requestParams['role'], $role) || $requestParams['role'] == User::ROLE_CLIENT) {
            $role = $auth->getRole($requestParams['role']);
            $auth->revokeAll($user->id);
            $auth->assign($role, $user->id);
        }
        return $user;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionLogout()
    {
        $token = (explode(" ", Yii::$app->request->headers['Authorization'])[1]);

        /**
         * @var $userSerivce UserServices
         */
        $userSerivce = Yii::$container->get(UserServices::class);
        $userSerivce->logoutByToken($token);
    }

    public function actionReport($action)
    {
        $filter = Yii::$app->request->getQueryParam('filter');
        $start = $filter['start'];
        $end = $filter['end'];
        if ($start == null || $end == null) {
            Yii::$app->response->statusCode = 422;
            return 'Date not valid';
        }
        if ($end - $start > 60 * 60 * 24 * 30 * 2) {
            Yii::$app->response->statusCode = 422;
            return 'Date should not exceed 60 days';
        }
        if ($action == 'diller') {
            $auth = Yii::$app->authManager;
            $ids = $auth->getUserIdsByRole('diller');
            $data = [];
            foreach ($ids as $id) {
                $diller = User::findOne($id);
                $query = User::find()
                    ->andWhere(['parent_id' => $id]);
                $query = $query->asArray()->all();
                $models = [];
                $price = null;
                $count = null;
                foreach ($query as $market) {
                    $product_count = Product::find()->select('sum(count) as count')
                        ->andWhere([
                            'from_user_id' => $market['id'],
                            'coming_outgo' => Product::COMING,
                            'status' => Product::STATUS_IMPLOMENTET
                        ])
                        ->andWhere(['between', 'created_at', $start, $end])->one();
                    $count = $count + $product_count['count'];

                    $products = Product::find()
                        ->andWhere([
                            'from_user_id' => $market['id'],
                            'coming_outgo' => Product::COMING,
                            'status' => Product::STATUS_IMPLOMENTET
                        ])
                        ->andWhere(['between', 'created_at', $start, $end])->each(500);

                    foreach ($products as $product) {
                        /**
                         * @var $product Product
                         */
                        $sum = $product->price * $product->count;
                        $price = $price + $sum;

                        if (!in_array($product->models_id, $models)) {
                            $models[] = $product->models_id;
                        }
                    }
                }
                $data[] = [
                    'id' => $diller->id,
                    'user' => $diller,
                    'model_count' => count($models),
                    'product_count' => $count,
                    'sum' => $price
                ];

            }
            return new ArrayDataProvider([
                'allModels' => $data
            ]);
        }
        if ($action == 'market') {
            if (($ids = $filter['diller_id']) !== null) {
                if (!is_numeric($ids)) {
                    Yii::$app->response->statusCode = 422;
                    return 'Diller_id not valid';
                }
                $users = User::find()->andWhere(['parent_id' => $ids])->all();
            }else {
                $auth = Yii::$app->authManager;
                $ids = $auth->getUserIdsByRole('market');
                $users = User::find()->andWhere(['id' => $ids])->all();
            }
            $data = [];
            foreach ($users as $market) {
                $price = null;
                $count = null;
                $models = [];
                $product_count = Product::find()->select('sum(count) as count')
                    ->andWhere([
                        'from_user_id' => $market['id'],
                        'coming_outgo' => Product::COMING,
                        'status' => Product::STATUS_IMPLOMENTET
                    ])
                    ->andWhere(['between', 'created_at', $start, $end])->one();
                $count = $count + $product_count['count'];

                $products = Product::find()
                    ->andWhere([
                        'from_user_id' => $market['id'],
                        'coming_outgo' => Product::COMING,
                        'status' => Product::STATUS_IMPLOMENTET
                    ])
                    ->andWhere(['between', 'created_at', $start, $end])->each(500);

                foreach ($products as $product) {
                    /**
                     * @var $product Product
                     */
                    $sum = $product->price * $product->count;
                    $price = $price + $sum;

                    if (!in_array($product->models_id, $models)) {
                        $models[] = $product->models_id;
                    }
                }
                $data[] = [
                    'id' => $market->id,
                    'user' => $market,
                    'model_count' => count($models),
                    'product_count' => $count,
                    'sum' => $price
                ];
            }
            return new ArrayDataProvider([
                'allModels' => $data
            ]);
        }

    }

}
