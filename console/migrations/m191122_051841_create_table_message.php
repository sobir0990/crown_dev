<?php

use yii\db\Migration;

/**
 * Class m191122_051841_create_table_message
 */
class m191122_051841_create_table_message extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'message' => $this->string(254),
            'status' => $this->integer(),
            'state' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'user_id' => $this->integer()
        ]);
        $this->createIndex('idx-messages-user_id', 'messages', 'user_id');
        $this->addForeignKey(
            'fk-messages-user_id-user-id',
            'messages',
            'user_id',
            'user',
            'id'
        );
        $this->addColumn('sms_message', 'message_id', $this->integer());
    }

    public function safeDown()
    {
        $this->dropIndex('idx-messages-user_id', 'messages');
        $this->dropForeignKey('fk-messages-user_id-user-id', 'messages');
        $this->dropTable('{{%messages}}');

        $this->dropColumn('sms_message', 'message_id');
    }
}
