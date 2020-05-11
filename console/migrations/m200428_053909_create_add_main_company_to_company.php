<?php

use yii\db\Migration;

/**
 * Class m200228_053909_create_add_column_coment_to_balans
 */
class m200428_053909_create_add_main_company_to_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('user', ['username', 'phone', 'is_main', 'status'], [
            ['Crown tools', '998935627708', 1, 10]
        ]);

        $this->batchInsert('auth_assignment', ['item_name', 'user_id'], [
            ['company', 2]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }


}
