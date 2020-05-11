<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m191001_050822_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            '[[id]]' => $this->primaryKey(),
            '[[title]]' => $this->string(500),
            '[[description]]' => $this->text(),
            '[[slug]]' => $this->string(500),
            '[[name]]' => $this->text(),
            '[[ext]]' => $this->string(20),
            '[[file]]' => $this->text(),
            '[[folder]]' => $this->text(),
            '[[domain]]' => $this->text(),
            '[[created_at]]' => $this->integer(255)->notNull(),
            '[[updated_at]]' => $this->integer(255)->notNull(),
            '[[user_id]]' => $this->integer(255),
            '[[status]]' => $this->integer(4),
            '[[upload_data]]' => $this->text(),
            '[[params]]' => $this->text(),
            '[[path]]' => $this->text(),
            '[[size]]' => $this->integer()
        ]);

        $this->createIndex(
            'idx-files-users-user-id',
            '{{%files}}',
            '[[user_id]]'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
