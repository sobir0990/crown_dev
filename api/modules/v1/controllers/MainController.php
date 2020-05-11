<?php

namespace api\modules\v1\controllers;

use common\components\ApiController;
use common\models\Categories;
use common\models\Models;
use common\models\User;
use common\modules\translations\models\Message;
use common\modules\translations\models\SourceMessage;
use yii\data\ArrayDataProvider;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

/**
 * @author Izzat <i.rakhmatov@list.ru>
 * @package minfin
 */
class MainController extends Controller
{

    public function actionIndex()
    {
        return array(
            'status' => true,
            'message' => 'Welcome to master API v1'
        );
    }

//    public function actionTest()
//    {
//            $faker = \Faker\Factory::create('ru_RU');
//            $pages = new Categories();
//            $pages->name = $faker->city;
//            $pages->save();
//            for ($i = 0; $i < 8; $i++) {
//                $model = new Models();
//                $model->name = 'model' . $faker->postcode;
//                $model->model = $faker->postcode;
//                $model->course = '9600';
//                $model->description = $faker->realText();
//                $model->category_id = $pages->id;
//                $model->save();
//            }
//
//        $faker = \Faker\Factory::create('en_EN');
//        for ($i = 0; $i < 8; $i++) {
//            $model = new User();
//            $model->username = $faker->company;
//            $model->name = $faker->name;
//            $model->email = $faker->email;
//            $model->phone = $faker->phoneNumber;
//            $model->pc = $faker->postcode;
//            $model->oked = $faker->postcode;
//            $model->inn= $faker->postcode;
//            $model->save();
//        }
//    }

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


}
