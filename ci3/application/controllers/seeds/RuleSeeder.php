<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RuleSeeder extends CI_Controller {

    public function index() {
        $this->load->database();
        $data = $data1 = $data2 = [];

        $data[] = [
            'name' => "مدیر",
            'slug' => 'manager',
            'description' => 'manager organization',
        ];
        $data[] = [
            'name' => "گزارشگر",
            'slug' => 'reporter',
            'description' => 'reporter organization',
        ];
        $this->db->insert_batch('rules', $data);
        $data1[] = [
            'user_account_id' => 2,
            'target_id' => 2,
            'target_table'=>'rules'
        ];
        $this->db->insert_batch('user_account_relations', $data1);
        $id=$this->db->insert_id();
        for ($i = 1; $i <= 1; $i++) {
            $data2[] = [
                'target_table' => 'user_account_relations',
                'target_id' => $id,
                'category_id'=>1
            ];
        }			
        $this->db->insert_batch('category_relation', $data2);
        echo 'ok';
    }
// 		
}
