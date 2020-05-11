<?php

namespace api\modules\v1\controllers\admin;

use common\models\User;
use Yii;
use common\models\Categories;
use common\models\search\CategoriesSearch;
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
 *   path="/v1/categories",
 *   tags={"categories"},
 *   summary="Create",
 *   description="Create",
 *   operationId="categoriesCreate",
 *   security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         description="Input data format",
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/Categories")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: Created",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Categories")
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
 *   path="/v1/categories/{id}",
 *   tags={"categories"},
 *   summary="View",
 *   description="View",
 *   operationId="categoriesView",
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
 *            @OA\Schema(ref="#/components/schemas/Categories")
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
 *   path="/v1/categories",
 *   tags={"categories"},
 *   summary="Index (List)",
 *   description="Index (List)",
 *   operationId="categoriesIndexList",
 *   security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Categories")
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
 *   path="/v1/categories/{id}",
 *   tags={"categories"},
 *   summary="Update",
 *   description="Update",
 *   operationId="categoriesUpdate",
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
 *             @OA\Schema(ref="#/components/schemas/Categories")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: update",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Categories")
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
 *   path="/v1/categories/{id}",
 *   tags={"categories"},
 *   summary="Delete",
 *   description="Delete",
 *   operationId="categoriesDelete",
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
 *            @OA\Schema(ref="#/components/schemas/Categories")
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
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends ApiController
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['options'],
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


    public $modelClass = Categories::class;
    public $modelSearch = CategoriesSearch::class;


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $requestParams = $this->requestParams();

        $query = Categories::find();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}
