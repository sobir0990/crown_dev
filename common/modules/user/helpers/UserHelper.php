<?php
/**
 * Created by PhpStorm.
 * User: jakhar
 * Date: 7/4/19
 * Time: 1:04 PM
 */

namespace common\modules\user\helpers;


class UserHelper
{

    public static function generatorForPassword($length = 4)
    {
        return strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length));
    }
}