<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_user_account_relations_table extends CI_Migration {
    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'user_account_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'target_table' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
            ],
            'target_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user_account_relations');
        $this->db->query("ALTER TABLE user_account_relations MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE user_account_relations MODIFY updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE user_account_relations ADD CONSTRAINT fk_user_account_relations_user_account FOREIGN KEY (user_account_id) REFERENCES user_account(id) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_uar_target ON user_account_relations(target_table, target_id, user_account_id)");
    }
    public function down() {
        $this->dbforge->drop_table('user_account_relations', TRUE);
    }
}