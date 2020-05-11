<?php

namespace common\bootstrap;

use common\modules\promotion\interfaces\iPromotionServices;
use common\modules\promotion\services\PromotionServices;
use jakharbek\sms\interfaces\SmsSenderInterface;
use jakharbek\sms\providers\playmobile\PlaymobileConnectionDTO;
use jakharbek\sms\providers\playmobile\PlaymobileDriver;
use yii\base\BootstrapInterface;

/**
 * Class SetUp
 * @package common\bootstrap
 */
class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;


        $container->setSingleton(SmsSenderInterface::class, function () {

            $dto = new PlaymobileConnectionDTO();
            $dto->originator = getenv("PLAYMOBILE_ORIGINATOR");
            $dto->username = getenv("PLAYMOBILE_USERNAME");
            $dto->password = getenv("PLAYMOBILE_PASSWORD");

            return new PlaymobileDriver($dto);
        });

//        $container->setSingleton(iPromotionServices::class, PromotionServices::class);
    }

}