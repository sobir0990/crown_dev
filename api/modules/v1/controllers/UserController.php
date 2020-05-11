<?php

namespace api\modules\v1\controllers;

use common\components\ApiController;
use common\models\Models;
use common\models\Product;
use common\models\search\ProductSearch;
use common\models\search\UserSearch;
use common\models\User;
use common\models\UserTokens;
use common\modules\user\forms\CreateUserForm;
use common\modules\user\forms\PhoneConfirm;
use common\modules\user\forms\SignInClientForms;
use common\modules\user\forms\SignInForm;
use common\modules\user\forms\UpdateUserForm;
use common\modules\user\forms\UserUpdateForms;
use common\modules\user\repositories\UserRepository;
use common\modules\user\services\UserServices;
use DomainException;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\di\NotInstantiableException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class UserController extends ApiController
{

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
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        $query = User::find();

        if (($role = Yii::$app->request->getQueryParam('filter')['role']) !== null) {
            $auth = Yii::$app->authManager;
            $query->andWhere(['"user".id' => $auth->getUserIdsByRole($role)]);
        }

        if (($parent_id = Yii::$app->request->getQueryParam('filter')['parent_id']) !== null) {
            $query->andWhere(['parent_id' => $parent_id]);
        }

        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', 'name', (string)$name]);
        }

        $query->andWhere(['status' => User::STATUS_ACTIVE]);
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

        if (($models_id = \Yii::$app->request->getQueryParam('filter')['models_id']) !== null) {
            $query->leftJoin('product', '"product".user_id = "user".id');
            $query->andWhere(['"product".models_id' => $models_id]);
        }

        $query->andWhere(['status' => User::STATUS_ACTIVE]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    //phone
    public function RequestParams()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        return $requestParams;
    }

    public function actionGetMe()
    {
        /**
         * @var $userRepostiroy UserRepository
         */
        $userRepostiroy = Yii::$container->get(UserRepository::class);
        try {
            $user = $userRepostiroy->getByID(Yii::$app->user->id);
        } catch (Exception $exception) {
            throw new DomainException('User is not auth', 401);
        }
        if ($user->status == User::STATUS_ACTIVE) {
            return $user;
        }

        Yii::$app->response->setStatusCode(401, 'User is not auth');
    }

    /**
     * @return array|bool|UserTokens|ActiveRecord|null
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function actionSignIn()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $form = new SignInForm();
        $form->setAttributes($requestParams);
        $token = $form->signin();
        return $token;
    }

    /**
     * @param $phone
     * @return array|ActiveRecord|null
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionApprovePhone($phone)
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $form = new PhoneConfirm();
        $form->setAttributes($requestParams);
        $form->phone = $phone;
        if (!($token = $form->confirm())) {
            Yii::$app->response->statusCode = 400;
            Yii::$app->response->statusText = "Bad Request";
            return $form->getErrors();
        }

        return $token;
    }


    ////front end
    public function actionGetMeClient()
    {
        /**
         * @var $userRepostiroy UserRepository
         */
        $userRepostiroy = Yii::$container->get(UserRepository::class);
        try {
            $user = $userRepostiroy->getByIDClient(Yii::$app->user->id);
        } catch (Exception $exception) {
            throw new DomainException('User is not auth', 401);
        }
        if ($user->status == User::STATUS_ACTIVE) {
            return $user;
        }

        Yii::$app->response->setStatusCode(401, 'User is not auth');
    }


    /**
     * @return array|bool|UserTokens|ActiveRecord|null
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function actionSignInClient()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $form = new SignInClientForms();
        $form->setAttributes($requestParams);
        return $form->signInClient();
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
     * @param $phone
     * @throws InvalidConfigException
     * @throws NotInstantiableException
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
     * @throws InvalidConfigException
     * @throws NotInstantiableException
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

    ////

    /**
     * @return array|User
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new CreateUserForm();
        $model->load($this->requestParams(), '');
        return $model->create();
    }


    /**
     * @param $user_id
     * @return array|bool|UserUpdateForms
     * @throws \yii\base\Exception
     */
    public function actionUpdate($user_id = null)
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        if ($user_id == null) {
            $user_id = Yii::$app->user->id;
        }
        $update = new UpdateUserForm(['id' => $user_id]);
        $update->load($requestParams, '');
        if (!($user = $update->update())) {
            Yii::$app->response->statusCode = 400;
            Yii::$app->response->statusText = "Bad Request";
            return $update->getErrors();
        }

        return $user;
    }


    /**
     * @return array|ActiveDataProvider
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionMyProfile()
    {
        $user = User::getByToken();
        $auth = Yii::$app->authManager;
        $role = $auth->getUserIdsByRole(User::ROLE_CLIENT);

        $query = Models::find()
            ->leftJoin('product', '"models".id = "product".models_id')
            ->leftJoin('user', '"product".user_id = "user".id')
//            ->andWhere(['"user".id' => $role])
            ->andWhere(['"product".user_id' => $user->id]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return array|ActiveDataProvider
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionMyProducts()
    {
        $user = User::getByToken();

        $query = Product::find()
            ->andWhere(['"product".user_id' => $user->id]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }


}
