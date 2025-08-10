<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_handler
{
    private $CI;
    private Users_model $users_model;
    private Media_model $media_model;
    private Security_handler $security;
    private Notification_model $notification_model;
    private Rule_model $rule_model;
    private Send_handler $send;
    private Functions_handler $function;
    public function __construct(
        Security_handler $security_handler,
        Rule_model $rule_model,
        Users_model $users_model,
        Media_model $media_model,
        Notification_model $notification_model,
        Send_handler $send,
    ){
		$this->CI =& get_instance();
        $this->rule_model = $rule_model;
        $this->users_model = $users_model;
        $this->media_model = $media_model;
        $this->notification_model = $notification_model;
        $this->security = $security_handler;
        $this->send = $send;
	}
    public function setCategoryHandler($function) {
        $this->function = $function;
    }

    // used
    public function subscribe($data){
        return (!empty($data) && !empty($this->get_user_account_id()) &&
        $this->users_model->edit_account_weher_id(['notification_token'=>json_encode($data)],$this->get_user_account_id())?
        ['status'=>'success']:
        ['status'=>'error']);
    }
    public function check_user_rule($account_id){
        if(!empty($account_id)&&intval($account_id)>0){
            $a=$this->rule_model->get_user_rule_info(intval($account_id));
            if(!empty($a)){
                if(!empty($a['categories'])) $this->CI->session->set_userdata('category_id',array_column($a['categories'],'id'));
                if(!empty($a['rule_info'])) $this->CI->session->set_userdata('rule',$a['rule_info']);
                if(!empty($a['accessible_users'])) $this->CI->session->set_userdata('rule_relation_controller',$a['accessible_users']);
            }
        }
    }
    public function reset_user_info_session(){
        if(($id=$this->get_user_id())!==false && !empty($id) && intval($id)>0 &&
        ($user_info=$this->users_model->select_where_id(intval($id)))!==false &&
        !empty($user_info) && !empty(end($user_info))){
            $this->CI->session->set_userdata('user_info',end($user_info));
        }
    }
    public function get_user_cordinates(){
        return ($this->CI->session->has_userdata('user_cordinates') && 
        !empty($this->CI->session->userdata('user_cordinates'))?
            $this->CI->session->userdata('user_cordinates'):
            null);
    }    
    public function get_user_location(){
        return ($this->CI->session->has_userdata('user_city') && 
        !empty($this->CI->session->userdata('user_city'))?
            $this->CI->session->userdata('user_city'):
            null);
    }
    public function get_user_address_id(){
        return ($this->CI->session->has_userdata('user_address_id') && 
        !empty($this->CI->session->userdata('user_address_id')) && 
        intval($this->CI->session->userdata('user_address_id'))>0?
            intval($this->CI->session->userdata('user_address_id')): 
            null);
    }
    public function get_user_category_id(){
        return ($this->CI->session->has_userdata('category_id') && 
        !empty($this->CI->session->userdata('category_id'))?
            $this->CI->session->userdata('category_id'):null);
    }
    public function get_user_id(){
        return ($this->CI->session->has_userdata('id') && 
        !empty($this->CI->session->userdata('id')) &&
        intval($this->CI->session->userdata('id'))>0?
            intval($this->CI->session->userdata('id')):
            null);
    }
    public function get_user_account_id(){
        return ($this->CI->session->has_userdata('account_id') && 
        !empty($this->CI->session->userdata('account_id')) &&
        intval($this->CI->session->userdata('account_id'))>0?
            intval($this->CI->session->userdata('account_id')):
            null);
    }
    public function get_user_mobile_id(){
        return ($this->CI->session->has_userdata('mobile_id') && 
        !empty($this->CI->session->userdata('mobile_id')) &&
        intval($this->CI->session->userdata('mobile_id'))>0?
            intval($this->CI->session->userdata('mobile_id')):
            null);
    }
    private function get_user_mobile(){
        if(($a=$this->get_user_mobile_id())!==false && !empty($a) && intval($a)>0 &&
        ($b=$this->users_model->select_mobile_where_id(intval($a)))!==false && !empty($b) && !empty(end($b)))
            return end($b);
        return null;
    }
    public function get_user_info(){
        if($this->get_user_account_id()){
            $a=$this->send->ip_handler();
            $this->CI->session->set_userdata('user_city',$a['city']??'');
            $this->CI->session->set_userdata('user_cordinates',['lat'=>$a['lat']??'','lon'=>$a['lon']??'']);
            $data=$this->users_model->get_full_user_data_by_account_id(intval($this->get_user_account_id()));
            $data['status']='success';
            return $data;
        }
        return ['status'=>'error'];
    }
    public function get_notifications($data){
        if(!empty($data) && $this->get_user_account_id() && ($a=$this->notification_model->get_unread_by_user_account_id($this->get_user_account_id(),$data['limit']??5,$data['offset']??0))!==false){
            $a['status']='success';
            return $a;
        }
        return ['status'=>'error'];
    }
    public function read_notifications($id){
        if(!empty($id) && intval($id)>0 && ($a=$this->get_user_account_id())!==false && !empty($a) && intval($a)>0)
            return ['status'=>'success','data'=>$this->notification_model->mark_as_read(intval($id),intval($a))];
        return ['status'=>'error'];
    }
    public function get_all_user_address(){
        return ($this->get_user_account_id()?$this->users_model->select_address_where_user_account_id($this->get_user_account_id()):null);
    }
    public function get_user_info_where_user_account($id){
        $result=[];
        if(!empty($id) && intval($id)>0) $result=$this->users_model->get_info_user_data_by_account_id(intval($id));
        return $result;
    }
    public function edit_user($data){
        if(!empty($data) && !empty($data['name']) && !empty($data['family']) &&
        ($id=$this->get_user_id())!==false && !empty($id) && intval($id)>0 &&
        ($mobile=$this->get_user_mobile())!==false && !empty($mobile) && 
        !empty($mobile['id']) && intval($mobile['id'])>0 &&
        $this->users_model->edit_weher_id(['name'=>$this->security->string_secutory_week_check($data['name']),'family'=>$this->security->string_secutory_week_check($data['family'])],intval($id))){
            $this->reset_user_info_session();
            if(!empty($data['image_id']) && intval($data['image_id'])>0 &&
            $this->users_model->edit_mobile_weher_id(['image_id'=>intval($data['image_id'])],intval($mobile['id']))){
                if(!empty($mobile['image_id']) && intval($mobile['image_id'])>0 &&    
                ($image=$this->media_model->select_where_id(intval($mobile['image_id'])))!==false && 
                !empty($image) && !empty(end($image)) && !empty(end($image)['id']) && intval(end($image)['id'])>0 && !empty(end($image)['url'])){
                    $this->media_model->remove_where_id(intval(end($image)['id']));
                    $this->media_model->remove_file(end($image)['url']);
                }
            }
            return ['status'=>'success'];
        }
        return ['status'=>'error'];
    }
    private function check_user(){
        if($this->get_user_category_id()){
            $user_category=array_map('intval',$this->get_user_category_id());
            if(in_array(1,$user_category)) return true;
        }
        return false;
    }
    public function get_users($data){
        if(!empty($data) && $this->check_user()){
            $this->function->category_filtter_for_place=true;
            $this->function->get_all_category_active();
            $data=$this->users_model->get_all_user($data['limit']??10,$data['offset']??0);
            return [
                'status'=>'success',
                'all_category'=>$this->function->category,
                'all_rule'=>$this->rule_model->all_rules(),
                'data'=>$data['data']??[],
                'has_more'=>$data['has_more']??false
            ];
        }
        return ['status'=>'error'];
    }
    public function user_submit($data) {
        if (!empty($data) && !empty($data['data']) && $this->check_user()) {
            if (!empty($data['edit'])) {
                return $this->users_model->edit_user_admin($data);
            } else {
                return $this->users_model->add_user_admin($data['data']);
            }
        }
        return ['status' => 'error', 'message' => 'Invalid data'];
    }
    public function enable_user($data){
        if($this->check_user() &&
        !empty($data) && !empty($data['id']) && intval($data['id'])>0 && $this->users_model->enable_user(intval($data['id'])))
            return ['status'=>'success'];
        return ['status'=>'error'];
    }
    public function disable_user($data){
        if($this->check_user() &&
        !empty($data) && !empty($data['id']) && intval($data['id'])>0 && $this->users_model->disable_user(intval($data['id'])))
            return ['status'=>'success'];
        return ['status'=>'error'];
    }
    
}