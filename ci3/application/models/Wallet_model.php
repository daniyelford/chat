<?php

class Wallet_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
	}
	private $tbl="payment";
	private $account_changes="payment_account_changes";
	private $info='user_info';
	private $cart="user_cart";
	private $cart_relation="user_cart_relations";
	private $order="orders";
    private $product='product';
	private function select_where_array_table($tbl,$arr){
	    return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr)?$this->db->get_where($tbl,$arr)->result_array():false);
	}
	private function select_where_id_table($tbl,$id){
	    return (!empty($tbl) && is_string($tbl) && !empty($id) && intval($id)>0?$this->select_where_array_table($tbl,['id'=>intval($id)]):false);
	}
    private function select_where_or_where_order_table($tbl,$where,$orwhere,$con){
	    if (!empty($tbl) && is_string($tbl) && !empty($where) && is_string($where) && !empty($orwhere) && is_string($orwhere) && !empty($con)){
            $this->db->where($where, $con);
            $this->db->or_where($orwhere, $con);
            return $this->db->order_by('created_at', 'DESC')->get($tbl)->result_array();
        }
        return false;
	}
    private function select_where_in_array_table($tbl,$key,$arr){
        if (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && !empty($key) && is_string($key)){
            $this->db->where_in($key, $arr);
            return $this->db->get($tbl)->result_array();
        }
        return [];
    }
    private function add_to_table($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert($tbl,$arr));
    }
    private function add_to_table_return_id($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert($tbl,$arr)?$this->db->insert_id():false);
    }
    private function edit_table($tbl,$arr,$where){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && !empty($where) && is_array($where) && $this->db->update($tbl,$arr,$where));
    }
    private function remove_where_array_in_table($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->delete($tbl, $arr));
    }
    // costum
    public function select_payment_info_where_user_account_id($id){
        return (!empty($id) && intval($id)>0 ? $this->select_where_or_where_order_table($this->tbl,'pay_money_user_account_id','give_money_user_account_id', intval($id)) : false);
    }
    public function select_carts_where_id($id){
        return (!empty($id) && intval($id)>0 ? $this->select_where_id_table($this->cart, $id) : false);
    }
    public function select_product_where_id($id){
        return (!empty($id) && intval($id)>0 ? $this->select_where_id_table($this->product, $id) : false);
    }
    public function check_card_belongs_to_user_account($card_id, $user_account_id) {
        $this->db->select('c.id');
        $this->db->from('user_account_relations uar');
        $this->db->join('user_cart c', 'c.id = uar.target_id AND uar.target_table = "user_cart"');
        $this->db->where('uar.user_account_id', $user_account_id);
        $this->db->where('uar.target_table', 'user_cart');
        $this->db->where('uar.target_id', $card_id);
        $this->db->where('c.deleted_at IS NULL', null, false);
        return $this->db->count_all_results() > 0;
    }
    public function select_carts_where_user_account_id($user_account_id) {
        if (empty($user_account_id) || !is_numeric($user_account_id)) return false;
        $this->db->select('uc.*');
        $this->db->from('user_account_relations uar');
        $this->db->join('user_cart uc', 'uc.id = uar.target_id AND uar.target_table = "user_cart"');
        $this->db->where('uar.user_account_id', $user_account_id);
        $this->db->where('uc.deleted_at IS NULL', null, false);
        return $this->db->get()->result_array();
    }
    public function select_carts_where_in_cart_ids($cart){
        return (!empty($cart) && is_array($cart) ? $this->select_where_in_array_table($this->cart, 'id', $cart): []);
    }
    public function select_orders_where_in_order_ids($orders){
        return (!empty($orders) && is_array($orders) ? $this->select_where_in_array_table($this->order, 'id', $orders): []);
    }
    public function add_cart_relation($arr){
        return (!empty($arr) && is_array($arr) && $this->add_to_table($this->cart_relation, $arr));
    }
    public function add_account_changes_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->account_changes,$arr):false);
    }
    public function add_cart_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->cart,$arr):false);
    }
    public function add_payement($arr){
        return (!empty($arr) && is_array($arr) && $this->add_to_table($this->tbl,$arr));
    }
    public function add_payement_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->tbl,$arr):false);
    }
    public function remove_cart_where_id($id){
	    return (!empty($id) && intval($id) && $this->edit_table($this->cart,['deleted_at' => date('Y-m-d H:i:s')],['id'=>intval($id)]));
	}

}