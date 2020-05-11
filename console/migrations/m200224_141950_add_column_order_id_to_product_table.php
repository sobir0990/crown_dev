<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market}}`.
 */
class m200224_141950_add_column_order_id_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'order_id', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'order_id');
    }
}
