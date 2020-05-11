<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_tokens}}`.
 */
class m191001_050441_create_user_tokens_table extends Migration
{
    const TABLE = '{{%user_tokens}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'last_used_at' => $this->integer(),
            'expires' => $this->integer(),
            'user_agent' => $this->string(),
            'token' => $this->string(128)->notNull(),
            'data' => $this->integer(),
            'status' => $this->integer(),
            'type' => $this->integer(),
            'phone' => $this->string()
        ]);

        $this->createIndex(
            'idx-user_tokens-user_id',
            self::TABLE,
            'user_id'
        );

        $this->addForeignKey(
            'fk-user_tokens-user_id-user-id',
            self::TABLE,
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropIndex(
            'idx-user_tokens-user_id',
            self::TABLE
        );

        $this->dropForeignKey(
            'fk-user_tokens-user_id-user-id',
            self::TABLE
        );

        $this->dropTable(self::TABLE);
    }
}
