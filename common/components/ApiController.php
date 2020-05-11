<?php

namespace common\components;

use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecordInterface;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\IndexAction;
use yii\rest\OptionsAction;
use yii\rest\Serializer;
use yii\rest\UpdateAction;
use yii\rest\ViewAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DefaultController
 * @package api\modules\v1
 */
abstract class ApiController extends Controller
{

    public $modelClass;
    public $modelSearch;

    /**
     * @var \yii\rest\Serializer
     */
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'data',
        'expandParam' => 'include'
    ];

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
                'languages' => array(
                    'oz',
                    'uz',
                    'ru',
                    'en'
                ),
                'formatParam' => '_f',
                'languageParam' => '_l',
            ],
            'rateLimiter' => [
                'class' => RateLimiter::class,
            ],
        ]);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => array(
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'dataFilter' => [
                    'class' => 'yii\data\ActiveDataFilter',
                    'searchModel' => $this->modelSearch,
                ],
            ),
            'view' => array(
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ),
            'create' => array(
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ),
            'update' => array(
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ),
            'options' => array(
                'class' => 'yii\rest\OptionsAction',
                'resourceOptions' => array('GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS', 'POST'),
                'collectionOptions' => array('GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS', 'POST'),
            ),
        );
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        if (isset($model)) {
            return $model;
        }

        if (is_array($id)) {
            throw new NotFoundHttpException("Object not found: " . implode($id));
        }

        throw new NotFoundHttpException("Object not found: $id");
    }

    /**
     * @param $id
     * @return mixed|string
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->softDelete();
        return 'success';
    }

    public function checkAccess($action, $model = null, $params = [])
    {
//        if ($action == 'index') {
//        print_r($params);exit();
//            return \Yii::$app->request->setBodyParams(['status' => 1]);
//        }
    }


    public function getFilteredData($query, $searchModel)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $filter = null;
        $dataFilter = new ActiveDataFilter();
        $dataFilter->searchModel = $searchModel;

        if ($dataFilter->load($this->RequestParams())) {
            $filter = $dataFilter->build();
        }

        if (!empty($filter)) {
            $dataProvider->query->andWhere($filter);
        }

        return $dataProvider;

    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function RequestParams()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        return $requestParams;
    }
}
