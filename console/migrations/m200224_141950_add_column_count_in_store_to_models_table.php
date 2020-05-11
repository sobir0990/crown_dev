<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market}}`.
 */
class m200224_141950_add_column_count_in_store_to_models_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
           $this->addColumn('models', 'count_in_store', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('models', 'count_in_store');
    }
}
