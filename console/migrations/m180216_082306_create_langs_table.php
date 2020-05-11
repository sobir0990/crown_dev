<?php

use yii\db\Migration;

/**
 * Handles the creation of table `langs`.
 * Список языков в системе
 */
class m180216_082306_create_langs_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%langs}}', [
            '[[lang_id]]' => $this->primaryKey(),
            '[[name]]' => $this->string(45),
            '[[code]]' => $this->string(3)->unique()->notNull(),
            '[[flag]]' => $this->text(),
            '[[status]]' => $this->boolean()->defaultValue(true),
        ]);

        $this->batchInsert('{{%langs}}', ['name', 'code', 'flag', 'status'], [
            ['Uz',   'oz', '', 1],
            ['Уз',   'uz', '', 1],
            ['Ру', 'ru', '', 1],
        ]);

        $this->createTable('{{%_system_message}}', [
            'id'       => $this->primaryKey(),
            'category' => $this->string(),
            'message'  => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%_system_message_translation}}', [
            'id'          => $this->integer(),
            'language'    => $this->string(11),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->createIndex(
            'index_system_message_translation_unique_language',
            '{{%_system_message_translation}}',
            ['id', 'language'],
            true
        );

        $this->addForeignKey(
            'fk_system_message_translation_message_id',
            '{{%_system_message_translation}}',
            'id',
            '{{%_system_message}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%langs}}');

        $this->dropTable('{{%_system_message}}');

        $this->dropTable('{{%_system_message_translation}}');
    }
}
