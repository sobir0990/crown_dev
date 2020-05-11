<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reference}}`.
 */
class m200131_130043_create_reference_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reference}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(254),
            'phone' => $this->string(254),
            'description' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reference}}');
    }
}
