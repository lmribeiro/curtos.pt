<?php

use yii\db\Migration;

/**
 * Class m200402_212254_create_table_link
 */
class m200402_212254_create_table_link extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('link', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'target' => $this->string()->notNull(),
            'short' => $this->string()->null(),
            'visit_count' => $this->integer()->notNull()->defaultValue(0),
            'expires_after' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('link');
    }

}
