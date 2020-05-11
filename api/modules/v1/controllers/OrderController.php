<?php


namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\models\Order;
use common\models\search\OrderSearch;
use common\models\User;
use common\modules\export\repositories\ReportRepository;
use common\modules\product\forms\CreateOrderClientForms;
use common\modules\product\forms\CreateOrderForms;
use common\modules\product\forms\CreateStoreForms;
use common\modules\product\forms\UpdateStatusForms;
use common\modules\product\forms\ValidateOrderForms;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class OrderController extends ApiController
{

    /**
     * @var string
     */
    public $modelClass = Order::class;
    public $modelSearch = OrderSearch::class;


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        return $actions;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
//                'except' => ['index'],
                'denyCallback' => function () {
                    throw new \DomainException("Access Denied");
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN, User::ROLE_COMPANY, User::ROLE_DILLER, User::ROLE_MARKET],
                    ],
                ],
            ]
        ]);
    }

    public function actionIndex()
    {
        $requestParams = $this->requestParams();

        $query = Order::find();

        if (($user_id = \Yii::$app->request->getQueryParam('filter')['user_id']) !== null) {
            $query->andWhere(['user_id' => $user_id]);
        }

        if (($from_user_id = \Yii::$app->request->getQueryParam('filter')['from_user_id']) !== null) {
            $query->andWhere(['from_user_id' => $from_user_id]);
        }

        if (($status = \Yii::$app->request->getQueryParam('filter')['status']) !== null) {
            $query->andWhere(['status' => $status]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return ActiveDataProvider
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUser()
    {
        $user = User::getByToken();

        $query = Order::find();

        $query->andWhere(['user_id' => $user->id]);

        if (($from_user_id = \Yii::$app->request->getQueryParam('filter')['from_user_id']) !== null) {
            $query->andWhere(['from_user_id' => $from_user_id]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     */
    public function actionMarket()
    {

        $user = User::getByToken();
        $query = Order::find();
        $query->andWhere(['"order".store_id' => $user->id]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return array|bool|Order
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new CreateOrderForms();
        $model->load($this->requestParams(), '');
        if ($order = $model->create()) {
            return $order;
        }
        Yii::$app->response->statusCode = 422;
        return $model->getErrors();
    }


    /**
     * @return array|bool|Order
     * @throws \yii\base\InvalidConfigException
     */
    public function actionValidate()
    {
        $model = new ValidateOrderForms();
        $model->load($this->requestParams(), '');
        if ($order = $model->validateOrder()) {
            return $order;
        }
        Yii::$app->response->statusCode = 422;
        return $model->getErrors();
    }


    /**
     * @return array|bool|Order
     * @throws \yii\base\InvalidConfigException
     */
    public function actionClient()
    {
        $model = new CreateOrderClientForms();
        $model->load($this->requestParams(), '');
        if ($order = $model->create()) {
            return $order;
        }
        Yii::$app->response->statusCode = 422;
        return $model->getErrors();
    }

    /**
     * @return array|bool|Order
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStore()
    {
        $model = new CreateStoreForms();
        $model->load($this->requestParams(), '');
        if ($order = $model->create()) {
            return $order;
        }
        Yii::$app->response->statusCode = 422;
        return $model->getErrors();
    }

    /**
     * @return bool|string
     * @throws NotFoundHttpException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionExportExcel()
    {
        $path = Yii::getAlias('@static/' . 'report/');
        $file = $path . 'report.xlsx';
        $start_date = Yii::$app->request->getQueryParam('filter')['start_date'];
        $end_date = Yii::$app->request->getQueryParam('filter')['end_date'];

        /**
         * @var $reportRepository ReportRepository
         */
        $reportRepository = Yii::$container->get(ReportRepository::class);
        $excel = $reportRepository->getOrderReport($start_date, $end_date);
        return getenv('STATIC_URL') . 'report/report.xls';
    }

    /**
     * @param $id
     * @return array|Order|UpdateStatusForms|null
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStatus($id)
    {
        $model = new UpdateStatusForms(['id' => $id]);
        $model->load($this->requestParams(), '');
        if ($store = $model->updateStatus()) {
            return $store;
        }
        return $model;
    }


}
