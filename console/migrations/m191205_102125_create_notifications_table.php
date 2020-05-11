<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notifications}}`.
 */
class m191205_102125_create_notifications_table extends Migration
{
    public function up()
    {
        $tableOptions = NULL;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notifications}}', [
            'notification_id' => $this->primaryKey(),
            'title' => $this->string(254),
            'message' => $this->text(),
            'status' => $this->integer(),
            'user_id' => $this->integer(),
            'type' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%notifications}}');
    }
}
