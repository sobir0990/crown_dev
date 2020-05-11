<?php

use yii\db\Migration;

/**
 * Class m200303_100650_create_table_order_message
 */
class m200303_100650_create_table_order_client extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_client',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer(),
                'from_user_id' => $this->integer(),
                'models_id' => $this->integer(),
                'category_id' => $this->integer(),
                'price' => $this->integer(),
                'count' => $this->integer(),
                'phone' => $this->string(254),
                'status' => $this->integer(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer()
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    $this->dropTable('order_client');
    }


}
