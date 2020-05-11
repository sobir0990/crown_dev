<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m200214_104959_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'price' => $this->double(),
            'selling_price' => $this->double(),
            'count' => $this->integer(),
            'coming_outgo' => $this->integer()->defaultValue(null),
            'user_id' => $this->integer(),
            'models_id' => $this->integer(),
            'models_category_id' => $this->integer(),
            'coming_data' => $this->integer(),
            'outgo_data' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'status' => $this->integer()->defaultValue(10),
        ]);

        $this->addForeignKey(
            'fk-product-models_category_id-categories-id',
            'product',
            'models_category_id',
            'categories',
            'id');

        $this->addForeignKey(
            'fk-product-models_id-models-id',
            'product',
            'models_id',
            'models',
            'id');

        $this->addForeignKey(
            'fk-product-user_id-user-id',
            'product',
            'user_id',
            'user',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-product-models_category_id-categories-id', 'product');
        $this->dropForeignKey('fk-product-models_id-models-id', 'product');
        $this->dropForeignKey('fk-product-user_id-user-id', 'product');
        $this->dropTable('{{%product}}');
    }
}
