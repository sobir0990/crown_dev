<?php

use yii\db\Migration;

/**
 * Class m200228_053909_create_add_column_coment_to_balans
 */
class m200428_053909_create_add_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('settings', 'android', $this->string(254));
        $this->addColumn('settings', 'ios', $this->string(254));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('settings', 'android');
        $this->dropColumn('settings', 'ios');
    }


}
