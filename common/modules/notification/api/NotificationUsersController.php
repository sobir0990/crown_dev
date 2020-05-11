<?php


namespace common\modules\notification\api;


use common\components\ApiController;
use common\modules\notification\models\NotificationUsers;
use common\modules\notification\models\NotificationUsersSearch;

class NotificationUsersController extends ApiController
{

    public $modelClass = NotificationUsers::class;
    public $modelSearch = NotificationUsersSearch::class;

}
