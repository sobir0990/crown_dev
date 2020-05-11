<?php

namespace common\modules\translations\modules\admin;
use common\modules\translations\models\Message;
use yii\filters\AccessControl;

/**
 * translations module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\translations\modules\admin\controllers';

    /**
     * @return array
     */
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Message::PERMESSION_ACCESS],
                    ],
                ],
            ],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
