<?php


namespace api\modules\v1\controllers;


use api\modules\v1\forms\ReferenceForm;
use common\components\ApiController;
use common\models\Reference;
use common\models\search\ReferenceSearch;

class ReferenceController extends ApiController
{
    public $modelClass = Reference::class;
    public $modelSearch = ReferenceSearch::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
        return $actions;
    }


    /**
     * @return bool|Reference
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionCreate()
    {
        $model = new ReferenceForm();
        $model->load($this->requestParams(), '');
        return $model->create();
    }
}
