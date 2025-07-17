<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_user_cart_table extends CI_Migration {
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
            'shomare_shaba' => [
                'type' => 'VARCHAR',
                'constraint' => 26,
                'null' => TRUE,
            ],
            'shomare_hesab' => [
                'type' => 'VARCHAR',
                'constraint' => 26,
                'null' => TRUE,
            ],
            'shomare_cart' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
                'null' => TRUE,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user_cart');
    }

    public function down() {
        $this->dbforge->drop_table('user_cart');
    }
}
