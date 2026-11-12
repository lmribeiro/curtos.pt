<?php

use yii\db\Migration;

class m261112_130000_cascade_link_stats_on_delete extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE {{link_stats}}
            DROP FOREIGN KEY [[fk-link_link_stats]],
            ADD CONSTRAINT [[fk-link_link_stats-cascade]] FOREIGN KEY ([[link_id]])
            REFERENCES {{link}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function safeDown()
    {
        $this->execute('ALTER TABLE {{link_stats}}
            DROP FOREIGN KEY [[fk-link_link_stats-cascade]],
            ADD CONSTRAINT [[fk-link_link_stats]] FOREIGN KEY ([[link_id]])
            REFERENCES {{link}} ([[id]]) ON DELETE SET NULL ON UPDATE CASCADE');
    }
}
