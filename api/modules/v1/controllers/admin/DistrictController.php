<?php


namespace api\modules\v1\controllers\admin;


use common\components\ApiController;
use common\models\District;
use common\models\search\DistirctSearch;

class DistrictController extends ApiController
{
    public $modelClass = District::class;
    public $modelSearch = DistirctSearch::class;


}