<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_products}}`.
 */
class m200214_113148_create_order_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_products}}', [
            'order_id' => $this->integer(),
            'product_id' => $this->integer(),
            'product_user_id' => $this->integer(),
            'product_models_id' => $this->integer(),
            'product_models_category_id' => $this->integer()
        ]);

//        $this->addForeignKey(
//            'fk-order_products-order_id-order-id',
//            'order_products',
//            'order_id',
//            'order',
//            'id');

        $this->addForeignKey(
            'fk-order_products-product_id-product-id',
            'order_products',
            'product_id',
            'product',
            'id');

        $this->addForeignKey(
            'fk-order_products-product_user_id-user-id',
            'order_products',
            'product_user_id',
            'user',
            'id');

        $this->addForeignKey(
            'fk-order_products-product_models_id-models-id',
            'order_products',
            'product_models_id',
            'models',
            'id');

        $this->addForeignKey(
            'fk-order_products-product_models_category_id-categories-id',
            'order_products',
            'product_models_category_id',
            'categories',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropForeignKey('fk-order_products-order_id-order-id', 'order_products');
        $this->dropForeignKey('fk-order_products-product_id-product-id', 'order_products');
        $this->dropForeignKey('fk-order_products-product_models_id-models-id', 'order_products');
        $this->dropForeignKey('fk-order_products-product_user_id-user-id', 'order_products');
        $this->dropForeignKey('fk-order_products-product_models_category_id-categories-id', 'order_products');
        $this->dropTable('{{%order_products}}');
    }
}
