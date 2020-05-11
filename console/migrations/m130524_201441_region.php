<?php

use yii\db\Migration;

class m130524_201441_region extends Migration
{
    public function up()
    {

        $this->createTable('{{%region}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(254),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%region}}');
    }
}
