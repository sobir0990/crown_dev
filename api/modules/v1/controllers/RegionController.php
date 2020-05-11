<?php

namespace api\modules\v1\controllers;

use Yii;
use common\models\Region;
use common\models\search\RegionSearch;
use common\components\ApiController;

/**
 * @OA\Post(
 *   path="/v1/region",
 *   tags={"region"},
 *   summary="Create",
 *   description="Create",
 *   operationId="regionCreate",
 *   security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         description="Input data format",
 *         @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(ref="#/components/schemas/Region")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: Created",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Region")
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
 *   path="/v1/region/{id}",
 *   tags={"region"},
 *   summary="View",
 *   description="View",
 *   operationId="regionView",
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
 *            @OA\Schema(ref="#/components/schemas/Region")
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
 *   path="/v1/region",
 *   tags={"region"},
 *   summary="Index (List)",
 *   description="Index (List)",
 *   operationId="regionIndexList",
 *   security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Region")
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
 *   path="/v1/region/{id}",
 *   tags={"region"},
 *   summary="Update",
 *   description="Update",
 *   operationId="regionUpdate",
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
 *             @OA\Schema(ref="#/components/schemas/Region")
 *         )
 *     ),
 *   @OA\Response(
 *        response=200,
 *        description="Scucess: update",
 *        @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(ref="#/components/schemas/Region")
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
 *   path="/v1/region/{id}",
 *   tags={"region"},
 *   summary="Delete",
 *   description="Delete",
 *   operationId="regionDelete",
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
 *            @OA\Schema(ref="#/components/schemas/Region")
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
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends ApiController
{

    /**
     * @var string
     */
    public $modelClass = Region::class;
    public $modelSearch = RegionSearch::class;

}
