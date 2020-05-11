<?php

namespace api\modules\v1\controllers\admin;

use common\models\Pages;
use common\models\User;
use Yii;
use common\models\Post;
use common\models\search\PostSearch;
use common\components\ApiController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends ApiController
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
        $post = Post::find()->andWhere(['slug' => $slug])->one();
        return $post;
    }

    /**
     * @return Post
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionView()
    {
        $slug = \Yii::$app->getRequest()->getQueryParams()['slug'];
        if (empty($slug)) {
            $slug = \Yii::$app->getRequest()->getBodyParams('slug');
        }

        return $this->findModelBySlug($slug);
    }


    /**
     * @param $slug
     * @return Post
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function findModelBySlug($slug): Post
    {
        $model = Post::findOne(['slug' => $slug]);
        if ($model instanceof Post) {
            return $model;
        }

        throw new NotFoundHttpException('Page is not founded');
    }

    public function actionDelete($id)
    {
        $model = Post::findOne($id);
        $model->delete();
    }

}
