<?php


namespace api\modules\v1\controllers\admin;


use api\modules\v1\forms\ReferenceForm;
use common\components\ApiController;
use common\models\Reference;
use common\models\ReferenceSearch;
use common\models\User;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ReferenceController extends ApiController
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

    public $modelClass = Reference::class;
    public $searchModelClass = ReferenceSearch::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionDelete($id)
    {
        $model = Reference::findOne($id);
        $model->delete();
    }
}
