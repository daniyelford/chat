<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('curl'); // برای ارسال درخواست HTTP به Node.js
        $this->load->database();
    }

    public function add_data() {
        // فرضاً داده از POST میاد
        $data = $this->input->post();

        // ذخیره داده در دیتابیس (مثال ساده)
        $this->db->insert('my_table', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // بعد از ذخیره، به Node.js خبر میدیم
            $this->notifyNodeJs($insert_id, $data);

            echo json_encode(['status' => 'success', 'id' => $insert_id]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    private function notifyNodeJs($id, $data) {
        $nodeUrl = "http://localhost:3000/notify";  // فرض آدرس سرور Node.js

        $payload = [
            'id' => $id,
            'data' => $data,
        ];

        // ارسال POST به Node.js
        $this->curl->simple_post($nodeUrl, $payload);
    }
}