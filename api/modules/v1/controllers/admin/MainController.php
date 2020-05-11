<?php

namespace api\modules\v1\controllers\admin;

use common\components\ApiController;
use common\modules\translations\models\Message;
use common\modules\translations\models\SourceMessage;
use yii\data\ArrayDataProvider;
use yii\web\ServerErrorHttpException;

/**
 * @author Izzat <i.rakhmatov@list.ru>
 * @package minfin
 */

class MainController extends ApiController
{

    public function actionIndex()
    {
        return array(
            'status' => true,
            'message' => 'Welcome to yoshlar API v1'
        );
    }

    /**
     * @param null $lang
     * @param string $category
     * @return array
     */
    public function actionTranslations($lang = null, $category = "react")
    {
        if ($lang == null) {
            $lang = \Yii::$app->language;
        }
        $translates = \Yii::$app->cache->get('getAllTranslates');

        if ($translates === false) {
            $translates = SourceMessage::find()->where(['category' => $category])->asArray()->all();
            \Yii::$app->cache->set('getAllTranslates', $translates, 7200);
        }

        $json = [];
        foreach ($translates as $translate) {
            $cacheKey = "messageByKeyId{$translate['id']}Lang{$lang}";
            $message = \Yii::$app->cache->get($cacheKey);

            if ($message === false) {
                $message = @Message::find()
                    ->where(['id' => $translate['id']])
                    ->andWhere(['LIKE', 'language', $lang])
                    ->asArray()
                    ->one();

                \Yii::$app->cache->set($cacheKey, $message, 7200);
            }

            $translate['systemMessageTranslation'] = $message;
            if (strlen(trim($translate['systemMessageTranslation']['translation'])) == 0) {
                $json[$translate['message']] = @$translate['message'];
                continue;
            }
            $json[$translate['message']] = @$translate['systemMessageTranslation']['translation'];

        }

        return $json;
    }

    /**
     * @param null $lang
     * @param string $category
     * @return array|bool|\yii\console\Response|\yii\web\Response
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAddTranslation($lang = null, $category = 'react')
    {
        if ($lang == null) {
            $lang = \Yii::$app->language;
        }
        $requestParams = \Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams();
        }
        if (count($requestParams) == 0) {
            throw new ServerErrorHttpException("Invalid data");
        }

        $sourceMessage = current($requestParams);
        $translateMessage = key($requestParams);

        $sm = SourceMessage::create($sourceMessage, $category);
        if (is_array($sm)) {
            \Yii::$app->getResponse()->setStatusCode(409);
            return $sm;
        } elseif ($sm === true) {
            return \Yii::$app->getResponse()->setStatusCode(201);
        } else {
            return \Yii::$app->getResponse()->setStatusCode(500);
        }

    }

    /**
     * @param string $category
     * @return ArrayDataProvider
     */
    public function actionGetTranslations($category = "react")
    {
        $sourses = SourceMessage::find()->andWhere(['category' => $category])->all();
        $data = [];

        foreach ($sourses as $key => $sours) {
            $data[$key] = [
                'id' => $sours->id,
                'message' => $sours->message,
                'uz' => Message::findOne(['id' => $sours->id, 'language' => 'uz'])->translation,
                'ru' => Message::findOne(['id' => $sours->id, 'language' => 'ru'])->translation,
                'en' => Message::findOne(['id' => $sours->id, 'language' => 'en'])->translation,
            ];
        }

        return new ArrayDataProvider([
            'allModels' => $data
        ]);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMessageTranslate()
    {
        $requestParams = \Yii::$app->request->getBodyParams();
        if ($requestParams == null) {
            $requestParams = \Yii::$app->request->getQueryParams();
        }

        if ($model = Message::findOne(['id' => $requestParams['id'], 'language' => $requestParams['language']])) {
            $model->updateAttributes([
                'translation' => $requestParams['translation']
            ]);
            return $this->getData($model);
        }

        $model = new Message();
        if ($model->load($requestParams, '') && $model->save()) {
            return $this->getData($model);
        }
        return $model->getErrors();
    }

    /**
     * @param $model
     * @return array
     */
    public function getData($model)
    {
        $data = [
            'id' => $model->id,
            'message' => SourceMessage::findOne($model->id)->message,
            'uz' => Message::findOne(['id' => $model->id, 'language' => 'uz'])->translation,
            'ru' => Message::findOne(['id' => $model->id, 'language' => 'ru'])->translation,
            'en' => Message::findOne(['id' => $model->id, 'language' => 'en'])->translation,
        ];
        return $data;
    }
}
