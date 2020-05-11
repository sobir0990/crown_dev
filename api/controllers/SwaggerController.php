<?php

namespace api\controllers;

use Yii;
use yii\filters\Cors;
use yii\helpers\Json;
use yii\rest\Controller;

/**
 * @OA\Info(
 *     description="«Crown»",
 *     version="1.0.0",
 *     title="Crown",
 *     @OA\Contact(
 *         email="info@oks.uz"
 *     )
 * )
 */
/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 */

/**
 * @OA\Get(
 *     path="/",
 *     description="Default Page",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response="default", description="Default page")
 * )
 */
class SwaggerController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Origin' => ['*'],
                'Access-Control-Allow-Headers' => ['*'],
                'Access-Control-Request-Method' => ['GET, POST, DELETE, PUT, PATCH, OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Expose-Headers' => ['*']
            ],
        ];
        return $behaviors;
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        return $action;
    }

    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        $openapi = \OpenApi\scan([Yii::getAlias('@api'), Yii::getAlias('@common')]);
        $data = \yii\helpers\Json::decode($openapi->toJson());
        return $data;
    }

}
