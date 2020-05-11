<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tokens}}`.
 */
class m190128_094620_create_sms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sms}}', [
            'id'  => $this->primaryKey(),
            'message'     => $this->text(),
            'message_id'     => $this->string(),
            'recipient' => $this->string(),
            'sender' => $this->string(),
            'status'    => $this->integer(),
            'log' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sms}}');
    }
}
