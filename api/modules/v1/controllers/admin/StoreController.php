<?php


namespace api\modules\v1\controllers\admin;


use common\components\ApiController;
use common\models\Product;
use common\models\search\UserSearch;
use common\models\User;
use common\modules\product\forms\CreateProductForms;
use common\modules\story\forms\CreateStoreForms;
use common\modules\story\forms\UpdateStoryForms;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class StoreController extends ApiController
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

    public $modelClass = User::class;
    public $searchModel = UserSearch::class;

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
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_STORE);

        $query = User::find()->andWhere(['"user".id' => $ids]);

        if (($company_id = \Yii::$app->request->getQueryParam('filter')['company_id']) !== null) {
            $query->andWhere(['parent_id' => $company_id]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return array|User
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new CreateStoreForms();
        $model->load($this->requestParams(), '');
        return $model->create();
    }


    /**
     * @param $id
     * @return array|bool|User|UpdateStoryForms|null
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = new UpdateStoryForms(['id' => $id]);
        $model->load($this->requestParams(), '');
        if ($store = $model->update()) {
            return $store;
        }
        return $model;
    }


    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionProductStore()
    {
        $model = new CreateProductForms();
        $model->load($this->requestParams(), '');
        return $model->create();
    }
}
