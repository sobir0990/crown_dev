<?php

use yii\db\Migration;

/**
 * Class m191120_104546_create_table_queue
 */
class m191120_104546_create_table_queue extends Migration
{
    public function safeUp()
    {
        $this->createTable('queue', [
            'id' => $this->primaryKey(),
            'channel' => $this->string(255),
            'job' => $this->binary(),
            'pushed_at' => $this->integer(11),
            'ttr' => $this->integer(11),
            'delay' => $this->integer(11),
            'priority' => $this->integer(11),
            'reserved_at' => $this->integer(11),
            'attempt' => $this->integer(11),
            'done_at' => $this->integer(11),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('queue');
    }
}
