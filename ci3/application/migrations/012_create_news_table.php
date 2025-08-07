<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_news_table extends CI_Migration {
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
            'privacy' => [
                'type' => 'ENUM("public", "private")',
                'default' => 'public',
                'null' => FALSE,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => FALSE,
            ],
            'status' => [
                'type' => 'ENUM("checking", "seen")',
                'default' => 'checking',
                'null' => FALSE,
            ],
            'show_status' => [
                'type' => 'ENUM("do", "dont")',
                'default' => 'do',
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
        $this->dbforge->create_table('news');
        $this->db->query("ALTER TABLE news MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE news MODIFY updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $indexes = [
            'idx_news_status_privacy' => 'CREATE INDEX idx_news_status_privacy ON news(status, privacy)',
            'idx_status_privacy_id'   => 'CREATE INDEX idx_status_privacy_id ON news(status, privacy, id)',
        ];
        foreach ($indexes as $indexName => $createQuery) {
            $res = $this->db->query("SHOW INDEX FROM news WHERE Key_name = '$indexName'");
            if ($res->num_rows() == 0) {
                $this->db->query($createQuery);
            }
        }
        // // $this->db->query("CREATE INDEX IF NOT EXISTS idx_news_id_desc ON news(id DESC)");
        // $this->db->query("CREATE INDEX IF NOT EXISTS idx_news_status_privacy ON news(status, privacy)");
        // $this->db->query("CREATE INDEX IF NOT EXISTS idx_status_privacy_id ON news(status, privacy, id)");
    }

    public function down() {
        $this->dbforge->drop_table('news');
    }
}
