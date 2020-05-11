<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification_users}}`.
 */
class m191205_102327_create_notification_users_table extends Migration
{
    const TABLE_NAME = "{{%notification_users}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = NULL;

        if( $this->db->driverName === 'mysql' )
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'notification_id' => $this->integer(),
            'user_id' => $this->integer(20),
            'status' => $this->integer()->defaultValue(0),
            'cron_send' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->createIndex(
            'idx-notification_users-notification_id',
            self::TABLE_NAME,
            'notification_id'
        );

        $this->addForeignKey(
            'fk-notification_users-notification_id-notifications-notification_id',
            self::TABLE_NAME,
            'notification_id',
            '{{%notifications}}',
            'notification_id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-notification_users-user_id',
            self::TABLE_NAME,
            'user_id'
        );

        $this->addForeignKey(
            'fk-notification_users-user_id-user-id',
            self::TABLE_NAME,
            'user_id',
            '{{%user}}',
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
            'idx-notification_users-notification_id',
            self::TABLE_NAME
        );

        $this->dropForeignKey(
            'fk-notification_users-notification_id-notifications-notification_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-notification_users-user_id',
            self::TABLE_NAME
        );

        $this->dropForeignKey(
            'fk-notification_users-user_id-user-id',
            self::TABLE_NAME
        );

        $this->dropTable(self::TABLE_NAME);
    }
}
