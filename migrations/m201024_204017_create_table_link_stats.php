<?php

use yii\db\Migration;

/**
 * Class m201024_204017_create_table_link_stats
 */
class m201024_204017_create_table_link_stats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('link_stats', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->null(),
            'ip' => $this->string(32),
            'os' => $this->string(32),
            'platform' => $this->string(32),
            'browser' => $this->string(32),
            'browserVersion' => $this->string(32),
            'city' => $this->string(),
            'region' => $this->string(),
            'country_code' => $this->string(2),
            'country_name' => $this->string(),
            'lat' => $this->string(32),
            'lng' => $this->string(32),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-link_link_stats', 'link_stats', 'link_id', 'link', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx-link-link_stats', 'link_stats',  ['link_id'] );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-link_link_stats', 'link_stats');
        $this->dropIndex('idx-link-link_stats', 'link_stats');
        $this->dropTable('link_stats');
    }
}
