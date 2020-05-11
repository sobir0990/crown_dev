<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tokens}}`.
 */
class m190128_094624_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id'  => $this->primaryKey(),
            'email' => $this->string(254),
            'phone' => $this->string(254),
            'address' => $this->string(254),
            'course' => $this->string(254),
            'longitude' => $this->string(254),
            'latitude' => $this->string(254),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
