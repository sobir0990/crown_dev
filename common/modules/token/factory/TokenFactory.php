<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 02.10.2019
 * Time: 10:44
 */

namespace common\modules\token\factory;


use common\models\UserTokens;
use common\modules\token\dto\CreateTokenDto;

class TokenFactory
{
    public static function create(CreateTokenDto $dto)
    {
        $token = new UserTokens();
        $token->user_id = $dto->user_id;
        $token->status = $dto->status;
        $token->type = $dto->type;
        $token->phone = $dto->phone;

        if (!$token->save()) {
            throw new \DomainException('Token not saved', 422);
        }
        return $token;
    }
}
