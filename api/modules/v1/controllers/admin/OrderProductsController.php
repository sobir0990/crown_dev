<?php

namespace api\modules\v1\controllers\admin;


use common\models\User;
use Yii;
use common\models\OrderProducts;
use common\models\search\OrderProductsSearch;
use common\components\ApiController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\UpdateAction;
use yii\rest\ViewAction;
use yii\rest\DeleteAction;
use \zakovat\api\actions\RestoreAction;

/**
 * @OA\Post(
 *   path="/v1/order-products",
 *   tags={"order-products"},
 *   summary="Create",
 *   description="Create",
 *   operationId="order-productsCreate",
 *   security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         description="Input data format",
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/OrderProducts")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: Created",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/OrderProducts")
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
 *   path="/v1/order-products/{id}",
 *   tags={"order-products"},
 *   summary="View",
 *   description="View",
 *   operationId="order-productsView",
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
 *            @OA\Schema(ref="#/components/schemas/OrderProducts")
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
 *   path="/v1/order-products",
 *   tags={"order-products"},
 *   summary="Index (List)",
 *   description="Index (List)",
 *   operationId="order-productsIndexList",
 *   security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/OrderProducts")
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
 *   path="/v1/order-products/{id}",
 *   tags={"order-products"},
 *   summary="Update",
 *   description="Update",
 *   operationId="order-productsUpdate",
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
 *             @OA\Schema(ref="#/components/schemas/OrderProducts")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: update",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/OrderProducts")
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
 *   path="/v1/order-products/{id}",
 *   tags={"order-products"},
 *   summary="Delete",
 *   description="Delete",
 *   operationId="order-productsDelete",
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
 *            @OA\Schema(ref="#/components/schemas/OrderProducts")
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
 * OrderProductsController implements the CRUD actions for OrderProducts model.
 */
class OrderProductsController extends ApiController
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
    public $modelClass = OrderProducts::class;
    public $modelSearch = OrderProductsSearch::class;



    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $requestParams = $this->requestParams();

        $query = OrderProducts::find();

        if (($user_id = \Yii::$app->request->getQueryParam('filter')['user_id']) !== null) {
            $auth = Yii::$app->authManager;
            $ids = $auth->getUserIdsByRole(User::ROLE_STORE);
            $query->andWhere(['id' => $ids]);
            $query->andWhere(['product_user_id' => $user_id]);
        }

        if (($order_id = \Yii::$app->request->getQueryParam('filter')['order_id']) !== null) {
            $query->andWhere(['order_id' => $order_id]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }


}
