<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Place_handler
{
    private User_handler $user;
    private Place_model $place_model;
    
    public function __construct(
        User_handler $user_handler,
        Place_model $place_model
    ){
        $this->place_model=$place_model;
        $this->user = $user_handler;
    }
    public function get_places(){
        return (!empty($this->user->get_user_account_id())?
        ['status'=>'success','data'=>$this->place_model->get_place_with_relations(),'cord'=>$this->user->get_user_cordinates()]:
        ['status'=>'error']);
    }
}