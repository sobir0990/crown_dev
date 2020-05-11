<?php

use yii\db\Migration;

/**
 * Class m200321_144822_create_add_column_to_order
 */
class m200321_144822_create_add_column_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'created_user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'created_user_id');
    }


}
