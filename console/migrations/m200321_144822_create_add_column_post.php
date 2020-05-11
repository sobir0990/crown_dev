<?php

use yii\db\Migration;

/**
 * Class m200321_144822_create_add_column_to_order
 */
class m200321_144822_create_add_column_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('post', 'publish_time', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('post', 'publish_time');
    }


}
