<?php


namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\models\Categories;
use common\models\Product;
use common\models\search\ProductSearch;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ProductController extends ApiController
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
                        'roles' => [User::ROLE_ADMIN, User::ROLE_MARKET, User::ROLE_DILLER, User::ROLE_COMPANY],
                    ],
                ],
            ]
        ]);
    }

    public $modelClass = Product::class;
    public $modelSearch = ProductSearch::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }


    public function actionIndex()
    {
        $requestParams = \Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams();
        }

        $query = Product::find();

        if (($user_id = \Yii::$app->request->getQueryParam('filter')['user_id']) !== null) {
            $query->andWhere(['user_id' => $user_id]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);

    }
}
