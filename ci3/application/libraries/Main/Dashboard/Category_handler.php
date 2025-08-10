<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category_handler
{
    private User_handler $user;
    private Functions_handler $function;
    private Category_model $category_model;
    public function __construct(
        User_handler $user_handler,
        Functions_handler $functions_handler,
        Category_model $category_model,
    ){
        $this->user = $user_handler;
        $this->function = $functions_handler;
        $this->category_model = $category_model;
	}
    private function check_user(){
        if($this->function->has_category_id()){
            $user_category=array_map('intval',$this->user->get_user_category_id());
            if(in_array(1,$user_category)) return true;
        }
        return false;
    }
    public function all_category($data){
        if(!empty($data) && $this->check_user()){
            $data=$this->category_model->get_all_categories($data['limit']??10,$data['offset']??0);
            return ['status'=>'success','data'=>$data['data']??[],'has_more'=>$data['has_more']??false];
        }
        return ['status'=>'error'];
    }
    public function add_category($data){
        if($this->check_user() && !empty($data) && !empty($data['data'])){
            $id=0;
            if(!empty($data['edit']) && intval($data['edit'])>0){
                if($this->category_model->edit_category($data['data'],['id' => intval($data['edit'])]))
                    $id=intval($data['edit']);
            }else{
                $id=$this->category_model->add_category_return_id($data['data']);
            }
            if(intval($id)>0){
                $data['data']['id']=$id;
                return ['status'=>'success','id'=>intval($id),'data'=>$data['data']];
            }
        }
        return ['status'=>'error'];
    }
    public function delete_category($data){
        if($this->check_user() && 
        !empty($data) && !empty($data['id']) && intval($data['id'])>0 && 
        $this->category_model->delete_category_with_relations(intval($data['id']))) 
            return ['status'=>'success'];
        return ['status'=>'error'];
    }
}