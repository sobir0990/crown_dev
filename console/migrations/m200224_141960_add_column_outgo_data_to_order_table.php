<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market}}`.
 */
class m200224_141960_add_column_outgo_data_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'outgo_data', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'outgo_data');
    }
}
