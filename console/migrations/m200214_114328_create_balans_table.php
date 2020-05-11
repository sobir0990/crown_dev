<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%balans}}`.
 */
class m200214_114328_create_balans_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%balans}}', [
            'id' => $this->primaryKey(),
            'amount' => $this->double(),
            'income_outgo' => $this->integer(),
            'user_id' => $this->integer(),
            'order_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-balans-user_id-user-id',
            'balans',
            'user_id',
            'user',
            'id');

        $this->addForeignKey(
            'fk-balans-order_id-order-id',
            'balans',
            'order_id',
            'order',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-balans-user_id-user-id', 'balans');
        $this->dropForeignKey('fk-balans-order_id-order-id', 'balans');
        $this->dropTable('{{%balans}}');
    }
}
