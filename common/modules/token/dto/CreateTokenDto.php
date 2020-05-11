<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 02.10.2019
 * Time: 10:41
 */
namespace common\modules\token\dto;
//namespace common\modules\token\dto;

class CreateTokenDto
{
    public $user_id;
    public $status;
    public $type;
    public $phone;
    public $expire_at;
}
