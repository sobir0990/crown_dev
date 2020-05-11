<?php

use yii\db\Migration;

/**
 * Handles the creation of table `smslog`.
 */
class m181210_063406_create_smslog_table extends Migration
{

    const TABLE = '{{%smslog}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('smslog', [
            'id' => $this->primaryKey(),
            'recipient' => $this->string(15)->notNull(),
            'message_id' => $this->bigInteger(20),
            'originator' => $this->string(),
            'text' => $this->string(160),
            'status' => $this->integer(2),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('smslog');
    }
}
