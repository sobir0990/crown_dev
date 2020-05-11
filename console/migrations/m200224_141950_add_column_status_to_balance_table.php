<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market}}`.
 */
class m200224_141950_add_column_status_to_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
           $this->addColumn('balans', 'status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('balans', 'status');
    }
}
