<?php

use yii\db\Migration;

/**
 * Class m200228_053909_create_add_column_coment_to_balans
 */
class m200228_053909_create_add_column_coment_to_balance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('balans', 'comment', $this->string(254));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('balans', 'comment');
    }


}
