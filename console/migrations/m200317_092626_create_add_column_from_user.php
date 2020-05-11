<?php

use yii\db\Migration;

/**
 * Class m200317_092626_create_add_column_from_user_id
 */
class m200317_092626_create_add_column_from_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'ball', $this->string(254));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('user', 'ball');
    }


}
