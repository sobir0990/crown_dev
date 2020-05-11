<?php

namespace api\modules\v1\controllers;

use common\models\Pages;
use Yii;
use common\models\Post;
use common\models\search\PostSearch;
use common\components\ApiController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;


/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends ApiController
{

    /**
     * @var string
     */
    public $modelClass = Post::class;
    public $modelSearch = PostSearch::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    /**
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $query = Post::find();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionBySlug()
    {
        $slug = \Yii::$app->request->getQueryParams('slug');
        $pages = Post::find()->andWhere(['slug' => $slug])->one();
        return $pages;
    }

}
