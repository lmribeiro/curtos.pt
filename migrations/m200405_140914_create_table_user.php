<?php

use yii\db\Migration;

/**
 * Class m200405_140914_create_table_user
 */
class m200405_140914_create_table_user extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'role' => "ENUM ('ADMIN', 'USER') NOT NULL DEFAULT 'USER'",
            'name' => $this->string(),
            'avatar' => $this->string(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'deleted' => $this->smallInteger()->notNull()->defaultValue(0),
            'deleted_at' => $this->timestamp()->null()
        ]);

        $this->addForeignKey('fk-link_user', 'link', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-link_user', 'link');
        $this->dropTable('user');
    }

}
