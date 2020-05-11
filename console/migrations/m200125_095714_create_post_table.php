<?php

use yii\db\Migration;

/**
 * Class m200125_095714_create_post
 */
class m200125_095714_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255),
            'title' => $this->string(255),
            'description' => $this->text(),
            'files' => $this->string(255),
            'content' => $this->text(),
            'type' => $this->tinyInteger(),
            'view' => $this->integer()->defaultValue(0),
            'status' => $this->tinyInteger()->defaultValue(1),
            'top' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('post');
    }


}
