<?php


namespace api\modules\v1\controllers\admin;

use common\components\ApiController;
use common\modules\notification\forms\NotificationCreateForms;
use common\modules\notification\models\Notifications;
use common\modules\notification\models\NotificationsSearch;
use yii\data\ActiveDataProvider;

class NotificationController extends ApiController
{
    public $modelClass = Notifications::class;
    public $modelSearch = NotificationsSearch::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        return $actions;
    }

    public function actionIndex()
    {
        $requestParams = $this->requestParams();

        $query = Notifications::find();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new NotificationCreateForms();
        $model->load($this->requestParams(), '');
        return $model->create();
    }


}
