<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market}}`.
 */
class m200224_141950_add_column_working_hours_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'working_hours', $this->string(254));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'working_hours');
    }
}
