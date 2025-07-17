<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model
{
    protected $table = 'notifications';
    public function add_return_id($arr){
        return (!empty($arr) && is_array($arr) && $this->db->insert($this->table,$arr)?$this->db->insert_id():false);
    }
    public function get_unread_by_user_account_id($user_id, $limit = 10, $offset = 0) {
        $this->db->from('notifications n');
        $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "notification"', 'inner');
        $this->db->where('uar.user_account_id', $user_id);
        $this->db->where('n.is_read', 'dont');
        $total = $this->db->count_all_results();
        $this->db->select('n.*');
        $this->db->from('notifications n');
        $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "notification"', 'inner');
        $this->db->where('uar.user_account_id', $user_id);
        // $this->db->where('n.is_read', 'dont');
        $this->db->order_by('n.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $data = $this->db->get()->result_array();
        return [
            'total' => $total,
            'data' => $data
        ];
    }
    public function mark_as_read($notification_id, $user_account_id) {
        $exists = $this->db
            ->select('1')
            ->from('user_account_relations')
            ->where('target_table', 'notification')
            ->where('target_id', $notification_id)
            ->where('user_account_id', $user_account_id)
            ->get()
            ->num_rows();

        if (!$exists) {
            return false;
        }
        return $this->db
            ->where('id', $notification_id)
            ->update($this->table, [
                'is_read' => 'seen',
                'read_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
