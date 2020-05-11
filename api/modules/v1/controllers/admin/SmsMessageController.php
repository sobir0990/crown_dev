<?php

namespace api\modules\v1\controllers\admin;

use common\models\User;
use common\modules\message\models\Messages;
use common\modules\message\models\SmsMessage;
use common\modules\message\models\SmsMessageSearch;
use common\components\ApiController;
use common\modules\message\services\MessageRepository;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseJson;
use yii\rest\OptionsAction;

class SmsMessageController extends ApiController
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

    public $modelClass = SmsMessage::class;
    public $searchModelClass = SmsMessageSearch::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return array(
            'view' => array(
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
            ),
            'options' => array(
                'class' => OptionsAction::class
            )
        );
    }

    public function actionIndex()
    {
        $requestParams = \Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams();
        }

        $query = SmsMessage::find();

        if (($message_id = Yii::$app->request->getQueryParam('filter')['message_id']) !== null) {
            $query->andWhere(['message_id' => $message_id]);
        }
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionProgressBar()
    {
        $messages = Messages::find()
//            ->andWhere(['state' => Messages::STATE_EMPTY])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $data = [];
        foreach ($messages as $message) {
            /**
             * @var $message Messages
             */
            $query = SmsMessage::find()->andWhere(['message_id' => $message->id]);
            $count = $query->count();
            $sms_message = SmsMessage::find()->andWhere(['message_id' => $message->id])->one();
            $inActive = $query
                ->andWhere(['status' => SmsMessage::STATUS_ACTIVE])
                ->count();

            if ($query) {
                $data[] = [
                    'message_id' => $message->id,
                    'message' => $message->message,
                    'role' => $sms_message->role,
                    'status' => $message->state,
                    'percent' => $inActive * 100 / $count
                ];
            }

            if ($inActive == $count) {
                $message->updateAttributes(['state' => Messages::STATE_FULL]);
            }
        }
        return new ArrayDataProvider([
            'allModels' => $data
        ]);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSendSms()
    {
        $requestParams = \Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->getQueryParams();
        }

        /**
         * @var $userRepository MessageRepository
         */
        $userRepository = Yii::$container->get(MessageRepository::class);
        $message = $userRepository->sendMessage($requestParams['id'], $requestParams['role'], $requestParams['message']);

        /**
         * @var $message Messages
         */
        $query = SmsMessage::find()->andWhere(['message_id' => $message->id]);
        $count = $query->count();
        $sms_message = SmsMessage::find()->andWhere(['message_id' => $message->id])->one();
        $inActive = $query
            ->andWhere(['status' => SmsMessage::STATUS_ACTIVE])
            ->count();
        if ($count == 0) {
            return null;
        }
        $data = [
            'message_id' => $message->id,
            'message' => $message->message,
            'role' => $sms_message->role,
            'status' => $message->state,
            'percent' => $inActive * 100 / $count
        ];

        if ($inActive == $count) {
            $message->updateAttributes(['state' => Messages::STATE_FULL]);
        }

        return $data;
    }

    /**
     * @return int|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionResendSms()
    {
        /**
         * @var $userRepository MessageRepository
         */
        $userRepository = Yii::$container->get(MessageRepository::class);
        return $userRepository->resendSms();
    }

    public function actionResendSmsMessage($id)
    {

        $requestParams = \Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->getQueryParams();
        }

        /**
         * @var $userRepository MessageRepository
         */
        $userRepository = Yii::$container->get(MessageRepository::class);
        $userRepository->returnSms($id);

        /**
         * @var $message Messages
         */
        $query = SmsMessage::find()->andWhere(['message_id' => $id]);
        $count = $query->count();
        $message = Messages::findOne($id);
        $sms_message = SmsMessage::find()->andWhere(['message_id' => $id])->one();
        $inActive = $query
            ->andWhere(['status' => SmsMessage::STATUS_ACTIVE])
            ->count();
        if ($count == 0) {
            return null;
        }
        $data = [
            'message_id' => $message->id,
            'message' => $message->message,
            'role' => $sms_message->role,
            'status' => $message->state,
            'percent' => $inActive * 100 / $count
        ];

        if ($inActive == $count) {
            $message->updateAttributes(['state' => Messages::STATE_FULL]);
        }

        return $data;
    }

}
