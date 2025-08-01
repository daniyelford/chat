<?php

class Category_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
	}
    private $tbl='category';
    private $relation='category_relation';
    private function select_where_array_table($tbl,$arr){
	    return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr)?$this->db->get_where($tbl,$arr)->result_array():false);
	}
	private function select_where_id_table($tbl,$id){
	    return (!empty($tbl) && is_string($tbl) && !empty($id) && intval($id)>0?$this->select_where_array_table($tbl,['id'=>$id]):false);
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
    public function select_category_where_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_id_table($this->tbl,intval($id)):false);
	}
    public function select_all_relation() {
        return $this->db->get($this->relation)->result_array();
    }
    public function select_relation_where_array($arr){
        return (!empty($arr) && is_array($arr)?$this->select_where_array_table($this->relation,$arr):false);
    }
    public function select_category_where_active(){
	    return $this->select_where_array_table($this->tbl,['status'=>'active']);
	}
    public function select_category_where_active_and_not_place(){
	    return $this->select_where_array_table($this->tbl,['status'=>'active','for_place'=>'no']);
	}
    public function insert_relation($arr){
        return (!empty($arr) && is_array($arr) && $this->db->insert($this->relation, $arr));
    }
    public function insert_relation_batch($arr){
        return (!empty($arr) && is_array($arr) && $this->db->insert_batch($this->relation, $arr));
    }
    public function check_changes(String $tbl,Int $id,Array $old,Array $new){
        if(!empty($tbl) && !empty($id))
            if(!empty($old) && is_array($old)){
                $old_category_ids=array_map('intval',array_column($old,'category_id'));
                if(!empty($old_category_ids)){
                    $category_defrante_id=array_diff($old_category_ids,$new);
                    if(!empty($category_defrante_id)){
                        $category_defrante_relation = array_filter($old, function($item) use ($category_defrante_id) {
                            return in_array(intval($item['category_id']), $category_defrante_id);
                        });
                        $category_defrante_relation_id = array_column($category_defrante_relation, 'id');
                        $this->db->where_in('id', $category_defrante_relation_id)->delete($this->relation);
                    }
                }
                if(!empty($new)){
                    foreach($new as $n){
                        if(!empty($n) && !in_array($n,$old_category_ids))
                            $this->insert_relation([
                                'target_table'=>$tbl,
                                'target_id'=>(int) $id,
                                'category_id'=>$n
                            ]);
                    }
                }
                return true;
            }
        return false;
    }
}