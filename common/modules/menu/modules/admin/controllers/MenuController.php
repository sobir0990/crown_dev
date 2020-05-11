<?php

namespace common\modules\menu\modules\admin\controllers;

use common\modules\menu\models\Menu;
use common\modules\menu\models\MenuSearch;
use jakharbek\langs\components\Lang;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class MenuController
 */
class MenuController extends \yii\web\Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id = null)
    {
        if ($id == null) {
            $id = Yii::$app->request->getQueryParams()['id'];
        }
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id
        ]);
    }

    public function actionCreate($id = null)
    {
        $model = new Menu();

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                $model->status = 1;
                $model->parent_id = $id;

                if ($model->save()) {
                    return [
                        'status' => true
                    ];
                }
                var_dump($model->getErrors());exit();
            }
        }

        return [
            'status' => false
        ];
    }

    public function actionUpdate($id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            if ($data = @json_decode(Yii::$app->request->post('data'), true)) {
                Menu::sortTree($data, $id);
                return [
                    'status' => true
                ];
            }
        }
        return [
            'status' => false
        ];
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreateMenu($id)
    {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;
            if ($model->save()) {
                return $this->redirect(['update-menu', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * @param null $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateMenu($id = null)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update-menu', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return Menu|null
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Menu::findOne($id);
        if ($model instanceof Menu) {
            return $model;
        }
        throw new NotFoundHttpException('Menu is not founded');
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            if (($model = $this->findModel($id))) {
                if ($model->delete()) {
                    return [
                        'status' => true
                    ];
                }
            }
        }
        return [
            'status' => false
        ];
    }

}
