<?php


namespace common\modules\product\forms;

use common\models\Balans;
use common\models\Product;
use yii\base\Model;

class AddBalanceForms extends Model
{
    public $amount;
    public $income_outgo;
    public $user_id;
    public $order_id;
    public $from_user_id;
    public $comment;
    public $created_at;
    public $updated_at;
    public $status;

    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['income_outgo', 'user_id', 'order_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['income_outgo', 'user_id', 'from_user_id', 'order_id', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string', 'max' => 254],
        ];
    }

    public function create(){
        $balance = new Balans();
        $balance->income_outgo = Product::COMING;
        $balance->amount = $this->amount;
        $balance->comment = $this->comment;
        $balance->user_id = $this->user_id;
        $balance->from_user_id = $this->from_user_id;
        $balance->status = Balans::STATUS_APPROVED;
        $balance->save();
        return $balance;
    }

}
