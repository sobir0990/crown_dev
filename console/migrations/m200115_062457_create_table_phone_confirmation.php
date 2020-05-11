<?php

use yii\db\Migration;

/**
 * Class m200115_062457_create_table_phone_confirmation
 */
class m200115_062457_create_table_phone_confirmation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('phone_confirmation', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(15),
            'code' => $this->string(20),
            'status' => $this->integer(2)->defaultValue(0),
            'created_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('phone_confirmation');
    }

}
