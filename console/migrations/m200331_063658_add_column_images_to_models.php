<?php

use yii\db\Migration;

/**
 * Class m200331_063658_add_column_images_to_models
 */
class m200331_063658_add_column_images_to_models extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('models', 'images', $this->string(254));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('models', 'images');
    }


}
