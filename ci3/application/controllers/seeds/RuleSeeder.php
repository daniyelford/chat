<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class RuleSeeder extends CI_Controller {
    public function index() {
        $this->load->database();
        $this->db->insert_batch('rules', [
            [
                'name' => "مدیر",
                'slug' => 'manager',
                'description' => 'manager organization',
            ],
            [
                'name' => "گزارشگر",
                'slug' => 'reporter',
                'description' => 'reporter organization',
            ],
        ]);
        $this->db->insert('user_account_relations', [
            'user_account_id' => 1,
            'target_id' => 1,
            'target_table'=>'rules'
        ]);
        $id=$this->db->insert_id();			
        $this->db->insert('category_relation', [
            'target_table' => 'user_account_relations',
            'target_id' => $id,
            'category_id'=>1
        ]);
        $this->db->insert('user_account_relations', [
            'user_account_id' => 2,
            'target_id' => 2,
            'target_table'=>'rules'
        ]);
        $child_id=$this->db->insert_id();			
        $this->db->insert('category_relation', [
            'target_table' => 'user_account_relations',
            'target_id' => $child_id,
            'category_id'=>1
        ]);
        $this->db->insert('rule_hierarchy',[
            'parent_user_account_relation_id'=>$id,
        	'child_user_account_relation_id'=>$child_id,
            'relation_type'=>'view'
        ]);
        echo 'ok';
    }	
}