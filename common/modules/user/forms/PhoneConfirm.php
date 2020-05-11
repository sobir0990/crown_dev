<?php
namespace common\modules\user\forms;

use common\models\User;
use common\models\UserTokens;
use common\modules\playmobile\models\PhoneConfirmation;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class PhoneConfirm extends Model
{
    public $phone;
    public $code;
    public $status;

    public function rules()
    {
        return [
            [['phone', 'code'], 'required'],
            [['phone', 'code', 'status'], 'string', 'max' => 254]
        ];
    }

    public function confirm()
    {
        $confirmation = PhoneConfirmation::find()
            ->andWhere(['phone' => $this->phone, 'code' => $this->code, 'status' => PhoneConfirmation::STATUS_UNCONFIRMED])
            ->one();

        if ($confirmation instanceof PhoneConfirmation) {
            $confirmation->updateAttributes(['status' => PhoneConfirmation::STATUS_CONFIRMED]);

            $user = User::findOne(['phone' => $this->phone]);
            $user->updateAttributes(['status' => User::STATUS_ACTIVE]);

            $token = UserTokens::find()
                ->andWhere(['user_id' => $user->id])
                ->orderBy(['id' => SORT_DESC])
                ->one();

            if ($token->status == User::STATUS_INACTIVE) {
                $token->updateAttributes(['status' => User::STATUS_ACTIVE]);
            }

            if (!$token) {
                Yii::$app->response->setStatusCode(422);
            }

            return $user;
        }

        throw new NotFoundHttpException('Неправильный номер телефона или код подтверждения');

    }
}
