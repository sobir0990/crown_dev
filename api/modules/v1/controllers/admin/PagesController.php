<?php


namespace api\modules\v1\controllers\admin;


use common\components\ApiController;
use common\models\Pages;
use common\models\PagesSearch;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;


/**
 * PagesController implements the CRUD actions for Pages model.
 */
class PagesController extends ApiController
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


    public $modelClass = Pages::class;
    public $modelSearch = PagesSearch::class;


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $query = Pages::find();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }


    public function actionBySlug()
    {
        $slug = \Yii::$app->request->getQueryParams('slug');
        $pages = Pages::find()->andWhere(['slug' => $slug])->one();
        return $pages;
    }

    /**
     * @param $id
     * @return mixed|string|void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = Pages::findOne($id);
        $model->delete();
    }

}
