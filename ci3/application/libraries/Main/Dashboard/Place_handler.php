<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Place_handler
{
    private User_handler $user;
    private Place_model $place_model;
    private Security_handler $security;
    public function __construct(
        User_handler $user_handler,
        Place_model $place_model,
        Security_handler $security,
    ){
        $this->place_model = $place_model;
        $this->user = $user_handler;
        $this->security = $security;
    }
    public function get_places($data){
        if (!empty($this->user->get_user_account_id())) {
            $id = isset($data['id']) ? (int)$data['id'] : null;
            $offset = isset($data['offset']) ? (int)$data['offset'] : 0;
            $limit = isset($data['limit']) ? (int)$data['limit'] : 10;
            $category_id = isset($data['category_id']) ? $data['category_id'] : null;
            $places = $this->place_model->get_place_with_relations($offset, $limit, $id, $category_id);
            return [
                'status' => 'success',
                'data' => $places['data']??[],
                'cord' => $this->user->get_user_cordinates(),
                'user_account_id' => $this->user->get_user_account_id(),
                'has_more' => $places['has_more']??false
            ];
        }
        return ['status' => 'error', 'message' => 'کاربر احراز هویت نشده است.'];
    }
    public function get_places_category($data){
        $offset = isset($data['offset']) ? (int)$data['offset'] : 0;
        $limit = isset($data['limit']) ? (int)$data['limit'] : 20;
        $search = isset($data['search']) ? trim($data['search']) : '';
        $get_all = isset($data['search']); 
        $categories = $this->place_model->get_categories($offset, $limit, $get_all, $search);
        return [
            'status' => 'success',
            'data' => $categories['data']??[],
            'has_more' => $categories['has_more']??false
        ];
    }
    public function add_place($data){
        $this->place_model->security=[$this->security, 'string_secutory_week_check'];
        if (isset($data['edit']) && !empty($data['edit']) && intval($data['edit'])>0) {
            $updated = $this->place_model->update_place(intval($data['edit']), $data);
            if ($updated) {
                return ['status' => 'success', 'id' => intval($data['edit'])];
            } else {
                return ['status' => 'error', 'message' => 'ویرایش مکان با خطا مواجه شد.'];
            }
        } else {
            $new_id = $this->place_model->insert_place($data);
            if ($new_id) {
                return ['status' => 'success', 'id' => $new_id];
            } else {
                return ['status' => 'error', 'message' => 'افزودن مکان با خطا مواجه شد.'];
            }
        }
    }
}