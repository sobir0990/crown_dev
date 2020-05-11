<?php

namespace api\modules\v1\controllers;

use common\models\Categories;
use common\models\Product;
use common\models\User;
use Yii;
use common\models\Models;
use common\models\search\ModelsSearch;
use common\components\ApiController;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
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
 *   path="/v1/models",
 *   tags={"models"},
 *   summary="Create",
 *   description="Create",
 *   operationId="modelsCreate",
 *   security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         description="Input data format",
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/Models")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: Created",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Models")
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
 *   path="/v1/models/{id}",
 *   tags={"models"},
 *   summary="View",
 *   description="View",
 *   operationId="modelsView",
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
 *            @OA\Schema(ref="#/components/schemas/Models")
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
 *   path="/v1/models",
 *   tags={"models"},
 *   summary="Index (List)",
 *   description="Index (List)",
 *   operationId="modelsIndexList",
 *   security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Models")
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
 *   path="/v1/models/{id}",
 *   tags={"models"},
 *   summary="Update",
 *   description="Update",
 *   operationId="modelsUpdate",
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
 *             @OA\Schema(ref="#/components/schemas/Models")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: update",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Models")
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
 *   path="/v1/models/{id}",
 *   tags={"models"},
 *   summary="Delete",
 *   description="Delete",
 *   operationId="modelsDelete",
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
 *            @OA\Schema(ref="#/components/schemas/Models")
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
 * ModelsController implements the CRUD actions for Models model.
 */
class ModelsController extends ApiController
{

    /**
     * @var string
     */
    public $modelClass = Models::class;
    public $modelSearch = ModelsSearch::class;

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
        $requestParams = $this->requestParams();

        $query = Models::find();

        if (($store_id = Yii::$app->request->getQueryParam('filter')['store_id']) !== null) {
            return new ArrayDataProvider([
                'allModels' => Models::getModelsByStoreId($store_id)
            ]);
        }

        if (($user_id = Yii::$app->request->getQueryParam('filter')['user_id']) !== null) {
            return new ArrayDataProvider([
                'allModels' => Models::getModelsByUserId($user_id)
            ]);
        }

        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', 'name', (string)$name]);
        }

        if (($category_id = \Yii::$app->request->getQueryParam('filter')['category_id']) !== null) {
            $query->andWhere(['category_id' => $category_id]);
        }

        if (($slider = \Yii::$app->request->getQueryParam('filter')['slider']) !== null) {
            $query->andWhere(['slider' => $slider]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionMarket()
    {
        $query = Models::find();

        if (($store_id = Yii::$app->request->getQueryParam('filter')['store_id']) !== null) {
            return new ArrayDataProvider([
                'allModels' => Models::getModelsByStoreId($store_id)
            ]);
        }

        if (($user_id = Yii::$app->request->getQueryParam('filter')['user_id']) !== null) {
            return new ArrayDataProvider([
                'allModels' => Models::getModelsByUserId($user_id)
            ]);
        }

        if (($name = Yii::$app->request->getQueryParam('filter')['name']) !== null) {
            $query->andWhere(['ILIKE', '"models".name', (string)$name]);
        }

        if (($category_id = \Yii::$app->request->getQueryParam('filter')['category_id']) !== null) {
            $query->andWhere(['category_id' => $category_id]);
        }

        if (($slider = \Yii::$app->request->getQueryParam('filter')['slider']) !== null) {
            $query->andWhere(['slider' => $slider]);
        }

        $query->rightJoin('categories', '"models".category_id = "categories".id')
            ->andWhere(['"categories".show' => Categories::SHOW]);
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

}
