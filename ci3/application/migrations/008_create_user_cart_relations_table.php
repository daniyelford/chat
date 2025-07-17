<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_user_cart_relations_table extends CI_Migration {
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
            'user_cart_id' => [
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
        $this->dbforge->create_table('user_cart_relations');
        $this->db->query("ALTER TABLE user_cart_relations MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE user_cart_relations MODIFY updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE user_cart_relations ADD CONSTRAINT fk_user_cart_relations_user_cart FOREIGN KEY (user_cart_id) REFERENCES user_cart(id) ON DELETE CASCADE ON UPDATE CASCADE");
    }
    public function down() {
        $this->dbforge->drop_table('user_cart_relations');
    }
}
