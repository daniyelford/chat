<?php

class Users_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
	}
	private $tbl="users";
	private $mobile='user_mobile';
	private $account='user_account';
	private $account_relations='user_account_relations';
	private $address='addresses';
	private $address_relation='address_relation'; 
	private $credential='user_credentials';
	private function select_where_array_table($tbl,$arr){
	    return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr)?$this->db->get_where($tbl,$arr)->result_array():false);
	}
	private function select_where_id_table($tbl,$id){
	    return (!empty($tbl) && is_string($tbl) && !empty($id) && intval($id)>0?$this->select_where_array_table($tbl,['id'=>intval($id)]):false);
	}
    private function add_to_table($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert($tbl,$arr));
    }
    private function add_all_to_table($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert_batch($tbl,$arr));
    }
    private function add_to_table_return_id($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert($tbl,$arr)?$this->db->insert_id():false);
    }
    private function edit_table($tbl,$arr,$where){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && !empty($where) && is_array($where) && $this->db->update($tbl,$arr,$where));
    }
    // costum
    // login used 
    public function select_where_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_id_table($this->tbl,intval($id)):false);
	}
    public function credential_where_user_mobile_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->credential,['user_mobile_id'=>intval($id)]):false);
	}
    public function credential_where_credential_id($id){
	    return (!empty($id) && is_string($id)?$this->select_where_array_table($this->credential,['credential_id'=>$id]):false);
	}
    public function add_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->tbl,$arr):false);
    }
    public function add_address_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->address,$arr):false);
    }
    public function select_mobile($str){
        return (!empty($str) && is_string($str)?$this->select_where_array_table($this->mobile,['phone'=>$str]):false);
	}
    public function select_account_where_mobile_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->account,['user_mobile_id'=>intval($id)]):false);
	}
    public function select_user_account_id_where_news_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->account_relations,['target_id'=>intval($id),'target_table' => 'news']):false);
	}
    public function add_account_relations($arr){
        return (!empty($arr) && is_array($arr) && $this->add_to_table($this->account_relations,$arr));
    }
    public function add_all_account_relations($arr){
        return (!empty($arr) && is_array($arr) && $this->add_all_to_table($this->account_relations,$arr));
    }
    public function add_credential($arr){
        return (!empty($arr) && is_array($arr) && $this->add_to_table($this->credential,$arr));
    }
    public function select_address_relation_where_news_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->address_relation,['target_id'=>intval($id),'target_table' => 'news']):false);
	}
    public function select_address_relation_where_report_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->address_relation,['target_id'=>intval($id),'target_table' => 'report_list']):false);
	}
    public function add_address_relation($arr){
        return (!empty($arr) && is_array($arr) && $this->add_to_table($this->address_relation,$arr));
    }
    public function edit_address_relation_weher_id($arr,$id){
        return (!empty($id) && intval($id)>0 && !empty($arr) && is_array($arr) && $this->edit_table($this->address_relation,$arr,['id'=>intval($id)]));
    }
    public function add_account_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->account,$arr):false);
    }
    public function add_account_relations_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->account_relations,$arr):false);
    }
    public function add_mobile_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->mobile,$arr):false);
    }
    public function edit_credential_where_id($arr,$id){
        return (!empty($id) && intval($id)>0 && !empty($arr) && is_array($arr) && $this->edit_table($this->credential,$arr,['id'=>intval($id)]));
    }
    public function edit_mobile_weher_id($arr,$id){
        return (!empty($id) && intval($id)>0 && !empty($arr) && is_array($arr) && $this->edit_table($this->mobile,$arr,['id'=>intval($id)]));
    }
    // login used
    public function get_full_user_data_by_account_id($user_account_id){
        if (empty($user_account_id) || !is_numeric($user_account_id)) return false;
        $this->db->select("
            user_account.*,
            user_account.balance AS wallet,
            user_account.token_score AS token,
            user_mobile.phone AS mobile,
            user_mobile.id AS user_mobile_id,
            user_mobile.image_id AS user_image_id,
            users.name AS name,
            users.family AS family,
            CONCAT(users.name, ' ', users.family) AS fullName,
            users.status AS user_status,
            media.url AS image
        ");
        $this->db->from('user_account');
        $this->db->join('user_mobile', 'user_account.user_mobile_id = user_mobile.id', 'left');
        $this->db->join('users', 'user_mobile.user_id = users.id', 'left');
        $this->db->join('media', 'user_mobile.image_id = media.id', 'left');
        $this->db->where('user_account.id', $user_account_id);
        $query = $this->db->get();
        $result = $query->row_array(); 
        if (!$result) return false;
        $this->db->select('r.id AS rule_id, r.name, r.slug, r.description, cr.category_id');
        $this->db->from('user_account_relations uar');
        $this->db->join('rules r', 'r.id = uar.target_id');
        $this->db->join('category_relation cr', 'cr.target_id = uar.id AND cr.target_table = "user_account_relations"');
        $this->db->where('uar.user_account_id', $user_account_id);
        $this->db->where('uar.target_table', 'rules');
        $rules = $this->db->get()->result_array();
        $this->db->from('user_credentials');
        $this->db->where('user_mobile_id', $result['user_mobile_id']);
        $this->db->limit(1);
        $credential_query = $this->db->get();
        $result['finger'] = $credential_query->num_rows() > 0;
        $result['rules'] = $rules;
        return $result;
    }
    public function get_info_user_data_by_account_id($user_account_id){
        if (empty($user_account_id) || !is_numeric($user_account_id)) return false;
        $this->db->select('
            user_account.*,
            user_mobile.phone AS phone,
            user_mobile.image_id AS user_image_id,
            users.name,
            users.family,
            users.status AS user_status,
            media.url AS image
        ');
        $this->db->from('user_account');
        $this->db->join('user_mobile', 'user_account.user_mobile_id = user_mobile.id', 'left');
        $this->db->join('users', 'user_mobile.user_id = users.id', 'left');
        $this->db->join('media', 'user_mobile.image_id = media.id', 'left');
        $this->db->where('user_account.id', $user_account_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function select_address_where_news(){
	    return $this->select_where_array_table($this->address,['status'=>'news']);
	}
	public function select_address_where_user_account_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->address,['user_account_id '=>intval($id)]):false);
	}
    public function select_mobile_where_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_id_table($this->mobile,intval($id)):false);
	}
    public function select_account_where_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_id_table($this->account,intval($id)):false);
	}
    public function add_address($arr){
        return (!empty($arr) && is_array($arr) && $this->add_to_table($this->address,$arr));
    }
    public function edit_weher_id($arr,$id){
        return (!empty($id) && intval($id)>0 && !empty($arr) && is_array($arr) && $this->edit_table($this->tbl,$arr,['id'=>intval($id)]));
    }
    public function edit_account_weher_id($arr,$id){
        return (!empty($id) && intval($id)>0 && !empty($arr) && is_array($arr) && $this->edit_table($this->account,$arr,['id'=>intval($id)]));
    }




    public function get_all_user($limit = 20, $offset = 0)
    {
        $this->db->select("
            user_account.*,
            user_account.balance AS wallet,
            user_account.token_score AS token,
            user_mobile.phone AS mobile,
            user_mobile.id AS user_mobile_id,
            user_mobile.image_id AS user_image_id,
            users.name AS name,
            users.family AS family,
            CONCAT(users.name, ' ', users.family) AS fullName,
            users.status AS user_status,
            media.url AS image
        ");
        $this->db->from('user_account');
        $this->db->join('user_mobile', 'user_account.user_mobile_id = user_mobile.id', 'left');
        $this->db->join('users', 'user_mobile.user_id = users.id', 'left');
        $this->db->join('media', 'user_mobile.image_id = media.id', 'left');
        $this->db->order_by('user_account.id', 'DESC');
        $this->db->limit($limit+1, $offset);
        $users = $this->db->get()->result_array();
        $has_more=(count($users)>$limit);
        if($has_more) array_pop($users);
        if(empty($users)) return ['data'=>[],'has_more'=>false];
        foreach ($users as &$user) {
             $this->db->select('
                r.id AS rule_id, 
                r.name, 
                r.slug, 
                r.description, 
                c.id AS category_id,
                c.name AS category_name,
                c.slug AS category_slug
            ');
            $this->db->from('user_account_relations uar');
            $this->db->join('rules r', 'r.id = uar.target_id');
            $this->db->join('category_relation cr', 'cr.target_id = uar.id AND cr.target_table = "user_account_relations"');
            $this->db->join('category c', 'c.id = cr.category_id', 'left');
            $this->db->where('uar.user_account_id', $user['id']);
            $this->db->where('uar.target_table', 'rules');
            $user['rules'] = $this->db->get()->result_array();
        }
        return ['data'=>$users,'has_more'=>$has_more];
    }





    public function save_relation($user_account_id, $target_table, $target_id, $category_id = null)
    {
        if (empty($user_account_id) || empty($target_table) || empty($target_id)) {
            return false;
        }

        // ذخیره در جدول user_account_relations
        $this->db->insert('user_account_relations', [
            'user_account_id' => $user_account_id,
            'target_id' => $target_id,
            'target_table' => $target_table
        ]);
        $relation_id = $this->db->insert_id();

        // اگر category_id داشت، ذخیره در category_relation
        if (!empty($category_id)) {
            $this->db->insert('category_relation', [
                'target_table' => 'user_account_relations',
                'target_id' => $relation_id,
                'category_id' => $category_id
            ]);
        }

        return $relation_id;
    }

    public function delete_relation($user_account_id, $target_table, $target_id)
    {
        if (empty($user_account_id) || empty($target_table) || empty($target_id)) {
            return false;
        }

        // پیدا کردن ID رابطه
        $this->db->select('id');
        $this->db->from('user_account_relations');
        $this->db->where([
            'user_account_id' => $user_account_id,
            'target_id' => $target_id,
            'target_table' => $target_table
        ]);
        $query = $this->db->get();
        $relation = $query->row_array();

        if ($relation) {
            // حذف از category_relation
            $this->db->where([
                'target_table' => 'user_account_relations',
                'target_id' => $relation['id']
            ]);
            $this->db->delete('category_relation');

            // حذف از user_account_relations
            $this->db->where('id', $relation['id']);
            $this->db->delete('user_account_relations');

            return true;
        }

        return false;
    }


    public function ban_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->update('users', [
            'status' => 'inactive',
            'ban_time' => date('Y-m-d H:i:s')
        ]);
        $this->db->select('user_account.id');
        $this->db->from('user_account');
        $this->db->join('user_mobile', 'user_mobile.id = user_account.user_mobile_id');
        $this->db->where('user_mobile.user_id', $user_id);
        $accounts = $this->db->get()->result_array();
        if (!empty($accounts)) {
            foreach ($accounts as $acc) {
                $this->db->where('target_table', 'news');
                $this->db->where('user_account_id', $acc['id']);
                $this->db->update('user_account_relations', ['show_status' => 'dont']);
                $this->db->where('target_table', 'report_list');
                $this->db->where('user_account_id', $acc['id']);
                $this->db->update('user_account_relations', ['show_status' => 'dont']);
            }
        }
    }

}