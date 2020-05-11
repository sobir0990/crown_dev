<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market}}`.
 */
class m200224_141960_add_column_store_id_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'store_id', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'store_id');
    }
}
