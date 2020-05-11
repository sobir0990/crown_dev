<?php


namespace api\modules\v1\controllers\admin;


use api\modules\v1\forms\UpdateSettingsForms;
use common\components\ApiController;
use common\models\Categories;
use common\models\Models;
use common\models\Order;
use common\models\Region;
use common\models\User;
use common\models\Settings;
use common\models\search\SettingsSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SettingsController extends ApiController
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

    public $modelClass = Settings::class;
    public $searchModel = SettingsSearch::class;


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        $query = Settings::findOne(1);
        return $query;
    }

    /**
     * @param $id
     * @return UpdateSettingsForms|null
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id){
        $model = new UpdateSettingsForms(['id' => $id]);
        $model->load($this->requestParams(), '');
        if ($store = $model->update()) {
            return $store;
        }
        return $model;
    }


    public function actionStatistic()
    {
        $categoryCount = Categories::find()->count();
        $regionCount = Region::find()->count();
        $modelsCount = Models::find()->count();

        return array_merge(
            [
                'categoryCount' => $categoryCount,
                'modelsCount' => $modelsCount,
                'regionCount' => $regionCount,
                'contact' => $this->getIsFill()
            ]
        );
    }

    public function getIsFill()
    {
        $model = Settings::find()->one();
        if ($model) {
            return true;
        } else {
            return false;
        }
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
       SELECT * FROM balans_coming
SQL;
        $coming = \Yii::$app->db->createCommand($total)->queryOne();

        $total = <<<SQL
       SELECT * FROM balans_outgo
SQL;
        $outgo = \Yii::$app->db->createCommand($total)->queryOne();

        $totalDebts = $coming['sum'] - $outgo['sum'];

        return array_merge(
            [
                'orders' => $order,
                'totalDebts' => $totalDebts,
                'storeCount' => $storeCount['storecount'],
                'storeProduct' => $storeProduct,
                'dillerCount' => $dillerCount['dillercount'],
                'marketCount' => $marketCount['marketcount'],
                'clientCount' => $clientCount['clientcount'],
                'preorder' => $preorder,
            ]
        );
    }

    public function actionChart()
    {

        $chart = Order::find()
            ->select(['TO_CHAR(to_timestamp("order"."created_at")::date, \'dd-MM-YYYY\') as month, SUM(price) as price'])
            ->groupBy('month')
            ->orderBy('month desc')
            ->andWhere(['status' => Order::STATUS_IMPLOMENTET])
            ->asArray()
            ->limit(30)
            ->all();

        return $chart;
    }

}
