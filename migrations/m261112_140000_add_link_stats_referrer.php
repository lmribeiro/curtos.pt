<?php

use yii\db\Migration;

class m261112_140000_add_link_stats_referrer extends Migration
{
    public function safeUp()
    {
        $this->addColumn('link_stats', 'referrer', $this->string(253)->null());
        $this->createIndex('idx-link_stats-link-created', 'link_stats', ['link_id', 'created_at']);
    }

    public function safeDown()
    {
        $this->dropIndex('idx-link_stats-link-created', 'link_stats');
        $this->dropColumn('link_stats', 'referrer');
    }
}
