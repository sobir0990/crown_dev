<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models}}`.
 */
class m200214_104243_create_models_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(254),
            'model' => $this->string(254),
            'description' => $this->text(),
            'price' => $this->double(),
            'files' => $this->string(254),
            'guarantee' => $this->string(254),
            'performance' => $this->text(),
            'category_id' => $this->integer(),
            'ball' => $this->integer()->defaultValue(0),
            'top' => $this->integer()->defaultValue(0),
            'slider' => $this->integer()->defaultValue(0),
            'recent' => $this->integer()->defaultValue(0),
            'course' => $this->double(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-models-category_id-categories-id',
            'models',
            'category_id',
            'categories',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-models-category_id-categories-id', 'models');
        $this->dropTable('{{%models}}');
    }
}
