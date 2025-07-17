<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_payment_order_table extends CI_Migration {
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
            'order_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'payment_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payment_order');
        $this->db->query("ALTER TABLE payment_order MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        $this->db->query('ALTER TABLE payment_order ADD CONSTRAINT fk_payment_order_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE payment_order ADD CONSTRAINT fk_payment_order_payment FOREIGN KEY (payment_id) REFERENCES payment(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down() {
        $this->dbforge->drop_table('payment_order');
    }
}
