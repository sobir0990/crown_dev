<?php

namespace api\modules\v1\controllers;

use common\components\ApiController;
use common\models\Settings;
use common\models\search\SettingsSearch;

class SettingsController extends ApiController
{
//    public function behaviors()
//    {
//        return ArrayHelper::merge(parent::behaviors(), [
//            'access' => [
//                'class' => AccessControl::className(),
//                //                'except' => ['index'],
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

    public $modelClass = Settings::class;
    public $searchModelClass = SettingsSearch::class;

    public function actionContact()
    {
        $query = Settings::findOne(1);

        return $query;
    }

    public function actionDashboard()
    {
        $diller = <<<SQL
       SELECT * FROM diller
SQL;
        $dillerCount = \Yii::$app->db->createCommand($diller)->queryOne();

        $client = <<<SQL
       SELECT * FROM client
SQL;
        $clientCount = \Yii::$app->db->createCommand($client)->queryOne();

        $market = <<<SQL
       SELECT * FROM market
SQL;
        $marketCount = \Yii::$app->db->createCommand($market)->queryOne();

        $store = <<<SQL
       SELECT * FROM store
SQL;
        $storeCount = \Yii::$app->db->createCommand($store)->queryOne();

        $orders = <<<SQL
       SELECT * FROM orders
SQL;
        $order = \Yii::$app->db->createCommand($orders)->queryOne();

        $preorders = <<<SQL
       SELECT * FROM preorder
SQL;
        $preorder = \Yii::$app->db->createCommand($preorders)->queryOne();

        $coming = <<<SQL
       SELECT * FROM store_coming
SQL;
        $storeComing = \Yii::$app->db->createCommand($coming)->queryOne();

        $outgo = <<<SQL
       SELECT * FROM store_outgo
SQL;
        $storeOutgo = \Yii::$app->db->createCommand($outgo)->queryOne();

        $storeProduct = $storeComing['sum'] - $storeOutgo['sum'];

        $total = <<<SQL
       SELECT * FROM store_outgo
SQL;
        $totalDebts = \Yii::$app->db->createCommand($total)->queryOne();

        return array_merge(
            [
                'orders' => $order,
                'totalDebts' => $totalDebts['sum'],
                'storeCount' => $storeCount['storecount'],
                'storeProduct' => $storeProduct,
                'dillerCount' => $dillerCount['dillercount'],
                'marketCount' => $marketCount['marketcount'],
                'clientCount' => $clientCount['clientcount'],
                'preorder' => $preorder,
            ]
        );
    }


}
