<?php


namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\models\Pages;
use common\models\PagesSearch;
use yii\data\ActiveDataProvider;


/**
 * PagesController implements the CRUD actions for Pages model.
 */
class PagesController extends ApiController
{

    public $modelClass = Pages::class;
    public $modelSearch = PagesSearch::class;

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
}
