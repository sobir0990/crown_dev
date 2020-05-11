<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(254),
            'name' => $this->string(254),
            'phone' => $this->string(32)->unique(),
            'parent_id' => $this->integer(),
            'pc' => $this->string(24),
            'bank' => $this->string(254),
            'files' => $this->string(254),
            'mfo' => $this->string(24),
            'inn' => $this->string(24),
            'oked' => $this->string(24),
            'longitude' => $this->string(254),
            'latitude' => $this->string(254),
            'email' => $this->string(254),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string(254)->defaultValue(null),
            'status' => $this->smallInteger()->defaultValue(9),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'region_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-user-region_id-region-id',
            'user',
            'region_id',
            'region',
            'id');

        $this->insert('{{%user}}', [
            'username' => 'admin',
            'phone' => '1234567',
            'auth_key' => "FHst5Kssfj3Sk",
            'password_hash' => \Yii::$app->security->generatePasswordHash("admin"),
            'password_reset_token' => '',
            'email' => 'info@oks.uz',
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

    }

    public function down()
    {
        $this->dropForeignKey('fk-user-region_id-region-id', 'user');
        $this->dropTable('{{%user}}');
    }
}
