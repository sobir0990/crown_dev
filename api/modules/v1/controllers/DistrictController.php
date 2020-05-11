<?php


namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\models\District;
use common\models\search\DistirctSearch;
use yii\data\ActiveDataProvider;

class DistrictController extends ApiController
{
    public $modelClass = District::class;
    public $modelSearch = DistirctSearch::class;

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
        $query = District::find()->all();
        return $query;
    }


}