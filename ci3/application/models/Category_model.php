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
        return (!empty($arr) && is_array($arr) && $this->add_to_table($this->relation, $arr));
    }
    public function insert_relation_batch($arr){
        return (!empty($arr) && is_array($arr) && $this->db->insert_batch($this->relation, $arr));
    }
    public function add_category_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->tbl,$arr):false);
    }
    public function edit_category($arr,$where){
        return (!empty($arr) && is_array($arr) && !empty($where) && is_array($where) && $this->edit_table($this->tbl,$arr,$where));
    }
    // costum
    public function get_all_categories($limit = 10, $offset = 0) {
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit+1, $offset);
        $data = $this->db->get($this->tbl)->result_array();
        $has_more=(count($data) > $limit);
        if ($has_more) array_pop($data);
        return ['data'=>$data??[],'has_more'=>$has_more??false];
    }
    public function delete_category_with_relations($id) {
        $this->remove_where_array_in_table($this->relation, ['category_id' => $id]);
        return $this->remove_where_array_in_table($this->tbl, ['id' => $id]);
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