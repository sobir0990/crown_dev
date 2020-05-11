<?php

use yii\db\Migration;

/**
 * Class m191120_104612_create_table_sms_message
 */
class m191120_104612_create_table_sms_message extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%sms_message}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'role' => $this->string(254),
            'message' => $this->string(254),
            'phone' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
        $this->createIndex('idx-sms_message-user_id', 'sms_message', 'user_id');
        $this->addForeignKey(
            'fk-sms_message-user_id-user-id',
            'sms_message',
            'user_id',
            'user',
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropIndex('idx-sms_message-user_id', 'sms_message');
        $this->dropForeignKey('fk-sms_message-user_id-user-id', 'sms_message');
        $this->dropTable('{{%sms_message}}');
    }

}
