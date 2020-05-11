<?php

use yii\db\Migration;

/**
 * Class m200219_130409_create_add_column_user
 */
class m200219_130409_create_add_column_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'address', $this->string(254));
        $this->addColumn('user', 'is_store', $this->integer());
        $this->addColumn('user', 'is_main', $this->integer());
        $this->addColumn('product', 'from_user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'address');
        $this->dropColumn('user', 'is_store');
        $this->dropColumn('user', 'is_main');
        $this->dropColumn('product', 'from_user_id');
    }


}
