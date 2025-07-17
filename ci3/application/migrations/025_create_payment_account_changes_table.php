<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_payment_account_changes_table extends CI_Migration {
    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'old_balance' => [
                'type' => 'BIGINT',
                'default' => 0,
            ],
            'new_balance' => [
                'type' => 'BIGINT',
                'default' => 0,
            ],
            'old_token' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'new_token' => [
                'type' => 'INT',
                'default' => 0,
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
        $this->dbforge->create_table('payment_account_changes');
        $this->db->query("ALTER TABLE payment_account_changes MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE payment_account_changes MODIFY updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

    public function down() {
        $this->dbforge->drop_table('payment_account_changes');
    }
}
