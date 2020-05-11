<?php


namespace api\modules\v1\controllers\admin;


use common\components\ApiController;
use common\models\NotificationUsers;
use common\models\search\NotificationUsersSearch;

class NotificationUsersController extends ApiController
{

    public $modelClass = NotificationUsers::class;
    public $modelSearch = NotificationUsersSearch::class;

}
