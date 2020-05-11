<?php


namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\models\Balans;
use common\models\Product;
use common\models\User;
use common\modules\product\forms\AddBalanceForms;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class BalansController extends ApiController
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

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    /**
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $requestParams = $this->requestParams();

        $user = User::authorize();
        $auth = Yii::$app->authManager;
        $query = Balans::find()
         ->andWhere(['"balans".status' => Balans::STATUS_APPROVED]);
        $query->leftJoin('user', '"balans".user_id = "user".id');

        if (($user_id = \Yii::$app->request->getQueryParam('filter')['user_id']) !== null) {
            $query->andWhere(['"balans".user_id' => $user->id]);

            if ($auth->getUserIdsByRole(User::ROLE_MARKET)) {
                $query->orWhere(['"balans".from_user_id' => $user->id]);
            }
        }

        if (($income_outgo = \Yii::$app->request->getQueryParam('filter')['income_outgo']) !== null) {
            $query->andWhere(['income_outgo' => $income_outgo]);
        }

        $start_date = Yii::$app->request->getQueryParam('filter')["balans".'start_date'];
        $end_date = Yii::$app->request->getQueryParam('filter')['"balans".end_date'];

        if ($end_date !== null || $start_date !== null) {
            if ($end_date == null || $start_date == null) {
                throw new \DomainException('Incorrect date', 400);
            }
            $query->andWhere(['between', 'created_at', $start_date, $end_date]);
        }


        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new AddBalanceForms();
        $model->load($this->requestParams(), '');
        return $model->create();
    }


}
