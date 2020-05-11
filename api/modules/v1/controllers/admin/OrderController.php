<?php

namespace api\modules\v1\controllers\admin;

use common\components\ExcelHelper;
use common\models\Categories;
use common\models\Models;
use common\models\Product;
use common\models\User;
use common\modules\export\interfaces\iReportInterface;
use common\modules\export\repositories\ReportRepository;
use common\modules\product\forms\UpdateOrderForms;
use common\modules\product\forms\UpdateStatusForms;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Yii;
use common\models\Order;
use common\models\search\OrderSearch;
use common\components\ApiController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * @OA\Post(
 *   path="/v1/order",
 *   tags={"order"},
 *   summary="Create",
 *   description="Create",
 *   operationId="orderCreate",
 *   security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         description="Input data format",
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/Order")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: Created",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Order")
 *       )
 *
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Error:Unauthorized",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                           @OA\Property(
 *                              property="name",
 *                              type="string"
 *                           ),
 *                           @OA\Property(
 *                              property="message",
 *                              type="string"
 *                          ),
 *                           @OA\Property(
 *                              property="code",
 *                              type="integer"
 *                          ),
 *                           @OA\Property(
 *                              property="status",
 *                              type="integer"
 *                          ),
 *                          @OA\Property(
 *                              property="type",
 *                              type="string"
 *                          ),
 *                          example={"name": "Unauthorized","message": "Your request was made with invalid credentials.","code":"0","status":"401","type": "yii\\web\\UnauthorizedHttpException"}
 *                      )
 *         )
 *     ),
 *
 *
 *     @OA\Response(
 *         response=422,
 *         description="Error:Validation",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                                  @OA\Property(
 *                                      property="field",
 *                                      type="string"
 *                                  ),
 *                                  @OA\Property(
 *                                      property="message",
 *                                     type="string"
 *                                  ),
 *                                  example={"field": "error_field","message": "Error message"}
 *                       )
 *                      )
 *     ),
 *  )
 *
 *
 *
 */

/**
 * @OA\Get(
 *   path="/v1/order/{id}",
 *   tags={"order"},
 *   summary="View",
 *   description="View",
 *   operationId="orderView",
 *   security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess:view",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Order")
 *       )
 *
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Error:Unauthorized",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                           @OA\Property(
 *                              property="name",
 *                              type="string"
 *                           ),
 *                           @OA\Property(
 *                              property="message",
 *                              type="string"
 *                          ),
 *                           @OA\Property(
 *                              property="code",
 *                              type="integer"
 *                          ),
 *                           @OA\Property(
 *                              property="status",
 *                              type="integer"
 *                          ),
 *                          @OA\Property(
 *                              property="type",
 *                              type="string"
 *                          ),
 *                          example={"name": "Unauthorized","message": "Your request was made with invalid credentials.","code":"0","status":"401","type": "yii\\web\\UnauthorizedHttpException"}
 *                      )
 *         )
 *     ),
 *
 *  )
 *
 *
 *
 */
/**
 * @OA\Get(
 *   path="/v1/order",
 *   tags={"order"},
 *   summary="Index (List)",
 *   description="Index (List)",
 *   operationId="orderIndexList",
 *   security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Order")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Error:Unauthorized",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                           @OA\Property(
 *                              property="name",
 *                              type="string"
 *                           ),
 *                           @OA\Property(
 *                              property="message",
 *                              type="string"
 *                          ),
 *                           @OA\Property(
 *                              property="code",
 *                              type="integer"
 *                          ),
 *                           @OA\Property(
 *                              property="status",
 *                              type="integer"
 *                          ),
 *                          @OA\Property(
 *                              property="type",
 *                              type="string"
 *                          ),
 *                          example={"name": "Unauthorized","message": "Your request was made with invalid credentials.","code":"0","status":"401","type": "yii\\web\\UnauthorizedHttpException"}
 *                      )
 *         )
 *     ),
 *
 *  )
 *
 *
 *
 */

/**
 * @OA\Put(
 *   path="/v1/order/{id}",
 *   tags={"order"},
 *   summary="Update",
 *   description="Update",
 *   operationId="orderUpdate",
 *   security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Input data format",
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/Order")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: update",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Order")
 *       )
 *
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Error:Unauthorized",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                           @OA\Property(
 *                              property="name",
 *                              type="string"
 *                           ),
 *                           @OA\Property(
 *                              property="message",
 *                              type="string"
 *                          ),
 *                           @OA\Property(
 *                              property="code",
 *                              type="integer"
 *                          ),
 *                           @OA\Property(
 *                              property="status",
 *                              type="integer"
 *                          ),
 *                          @OA\Property(
 *                              property="type",
 *                              type="string"
 *                          ),
 *                          example={"name": "Unauthorized","message": "Your request was made with invalid credentials.","code":"0","status":"401","type": "yii\\web\\UnauthorizedHttpException"}
 *                      )
 *         )
 *     ),
 *
 *  )
 *
 *
 *
 */


/**
 * @OA\Delete(
 *   path="/v1/order/{id}",
 *   tags={"order"},
 *   summary="Delete",
 *   description="Delete",
 *   operationId="orderDelete",
 *   security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of ",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: deleted",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Order")
 *       )
 *
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Error:Unauthorized",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                           @OA\Property(
 *                              property="name",
 *                              type="string"
 *                           ),
 *                           @OA\Property(
 *                              property="message",
 *                              type="string"
 *                          ),
 *                           @OA\Property(
 *                              property="code",
 *                              type="integer"
 *                          ),
 *                           @OA\Property(
 *                              property="status",
 *                              type="integer"
 *                          ),
 *                          @OA\Property(
 *                              property="type",
 *                              type="string"
 *                          ),
 *                          example={"name": "Unauthorized","message": "Your request was made with invalid credentials.","code":"0","status":"401","type": "yii\\web\\UnauthorizedHttpException"}
 *                      )
 *         )
 *     ),
 *
 *  )
 *
 *
 *
 */

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends ApiController
{

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
                        'roles' => [User::ROLE_ADMIN, User::ROLE_COMPANY],
                    ],
                ],
            ]
        ]);
    }

    /**
     * @var string
     */
    public $modelClass = Order::class;
    public $modelSearch = OrderSearch::class;



    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        $requestParams = $this->requestParams();

        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_DILLER);
        $query = Order::find()
            ->leftJoin('user', '"order".user_id = "user".id')
            ->andWhere(['"user".id' => $ids]);

        if (($user_id = \Yii::$app->request->getQueryParam('filter')['user_id']) !== null) {
            $query->andWhere(['user_id' => $user_id]);
        }

        if (($status = \Yii::$app->request->getQueryParam('filter')['status']) !== null) {
            $query->andWhere(['status' => $status]);
        }

        if (($company_id = \Yii::$app->request->getQueryParam('filter')['company_id']) !== null) {
//            $query->leftJoin('user', '"order".store_id = "user".id');
            $query->andWhere(['"user".parent_id' => $company_id]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionUpdate($id)
    {
        $requestParams = $this->requestParams();
        $model = new UpdateOrderForms(['id' => $id]);
        $model->store_id = $requestParams['store_id'];
        $model->total_price = $requestParams['total_price'];
        $model->outgo_data = $requestParams['outgo_data'];
        $model->models = $requestParams['models'];
        $model->status = $requestParams['status'];
        $model->user_id = $requestParams['user_id'];
        if ($order = $model->updateOrder()) {
            return $order;
        }
        Yii::$app->response->statusCode = 422;
        return $model->getErrors();
    }

    /**
     * @param $id
     * @return array|UpdateStatusForms
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
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

    /**
     * @return bool|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionExportExcel(){
        $start_date = Yii::$app->request->getQueryParam('filter')['start_date'];
        $end_date = Yii::$app->request->getQueryParam('filter')['end_date'];

        /**
         * @var $reportRepository ReportRepository
         */
        $reportRepository = Yii::$container->get(ReportRepository::class);
        $excel = $reportRepository->getOrderReport($start_date, $end_date);
        return $excel;
    }

}
