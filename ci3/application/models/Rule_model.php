<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule_model extends CI_Model {

    private $tbl='rules';
    public function get_user_rule_info($user_account_id) {
        $this->db->select('uar.id AS relation_id, r.id AS rule_id, r.name, r.slug, r.description');
        $this->db->from('user_account_relations uar');
        $this->db->join('rules r', 'r.id = uar.target_id');
        $this->db->where('uar.user_account_id', $user_account_id);
        $this->db->where('uar.target_table', 'rules');
        $rule = $this->db->get()->row();
        if (!$rule) {
            return [];
        }
        $rule_id = (int) $rule->rule_id;
        $relation_id = (int) $rule->relation_id;
        $this->db->select('c.*');
        $this->db->from('category_relation cr');
        $this->db->join('category c', 'c.id = cr.category_id');
        $this->db->where('cr.target_table', 'user_account_relations');
        $this->db->where('cr.target_id', $relation_id);
        $this->db->where('c.status', 'active');
        $categories = $this->db->get()->result();
        $this->db->select('uar.user_account_id');
        $this->db->from('rule_hierarchy rh');
        $this->db->join('user_account_relations uar', 'uar.id = rh.child_user_account_relation_id');
        $this->db->where('rh.parent_user_account_relation_id', $relation_id);
        $accessible_users = $this->db->get()->result();
        return [
            'rule_id' => $rule_id,
            'rule_info' => $rule,
            'categories' => $categories,
            'accessible_users' => array_column($accessible_users, 'user_account_id'),
        ];
    }
    public function get_users_rule_in_category($category_id) {
        $this->db->select('cr.target_id');
        $this->db->from('category_relation cr');
        $this->db->where_in('cr.category_id', $category_id);
        $this->db->where('cr.target_table', 'user_account_relations');
        $relations = $this->db->get()->result();
        if (!$relations) {
            return [];
        }
        $user_account_relation_ids = array_map(function($item) {
            return $item->target_id;
        }, $relations);
        $this->db->select('uar.user_account_id');
        $this->db->from('user_account_relations uar');
        $this->db->where_in('uar.id', $user_account_relation_ids);
        $this->db->where('uar.target_table', 'rules');
        $user_accounts = $this->db->get()->result_array();
        return $user_accounts;
    }
    public function all_rules(){
        return $this->db->get($this->tbl)->result_array();
    }
}
// public function force_action($category,$news_id){
//     $force = $this->db
//         ->select('is_force')
//         ->from('category')
//         ->where_in('id', $category)
//         ->where('is_force', 'yes')
//         ->get()
//         ->num_rows() > 0;
//     if ($force) {
//         $address_ids = $this->db->select('target_id')
//         ->from('address_relation')
//         ->where('target_table', 'news')
//         ->where('target_id', $news_id)
//         ->get()
//         ->result_array();
//         $address_ids = array_column($address_ids, 'target_id');
//         if (empty($address_ids)) return [];
//         $cities = $this->db->select('city')
//         ->from('addresses')
//         ->where_in('id', $address_ids)
//         ->get()
//         ->result_array();
//         $cities = array_unique(array_column($cities, 'city'));
//         if (empty($cities)) return [];
//         $city_address_ids = $this->db->select('id')
//         ->from('addresses')
//         ->where_in('city', $cities)
//         ->get()
//         ->result_array();
//         $city_address_ids = array_column($city_address_ids, 'id');
//         if (empty($city_address_ids)) return [];
//         $user_account_relations = $this->db->select('target_id')
//         ->from('address_relation')
//         ->where('target_table', 'user_account')
//         ->where_in('address_id', $city_address_ids)
//         ->get()
//         ->result_array();
//         $user_ids = array_unique(array_column($user_account_relations, 'target_id'));
//         if (empty($user_ids)) return [];
//         $tokens = $this->db->select('notification_token')
//         ->from('user_account')
//         ->where_in('id', $user_ids)
//         ->where('notification_token IS NOT NULL')
//         ->get()
//         ->result_array();
//         $tokens = array_column($tokens, 'notification_token');
//         if(!empty($tokens)) return $tokens;
//     }
//     return [];
// }
