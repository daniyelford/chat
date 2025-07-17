<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_addresses_table extends CI_Migration {
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
            'address' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'code_posti' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
            ],
            'region' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
            ],
            'lat' => [
                'type' => 'DECIMAL',
                'constraint' => '10,7',
                'null' => TRUE,
            ],
            'lon' => [
                'type' => 'DECIMAL',
                'constraint' => '10,7',
                'null' => TRUE,
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
            ],
            'mobile' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
            ],
            'proxy' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('addresses');
    }

    public function down() {
        $this->dbforge->drop_table('addresses');
    }
}
