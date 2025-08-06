<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Place_handler
{
    private User_handler $user;
    private Place_model $place_model;
    
    public function __construct(
        User_handler $user_handler,
        Place_model $place_model
    ){
        $this->place_model = $place_model;
        $this->user = $user_handler;
    }
    public function get_places($data){
        if (empty($this->user->get_user_account_id())) {
            return ['status' => 'error', 'message' => 'کاربر احراز هویت نشده است.'];
        }
        $offset = isset($data['offset']) ? (int)$data['offset'] : 0;
        $limit = isset($data['limit']) ? (int)$data['limit'] : 10;
        $category_id = isset($data['category_id']) ? $data['category_id'] : null;
        $places = $this->place_model->get_place_with_relations($offset, $limit, $category_id);
        $totalCount = $this->place_model->count_places($category_id);
        $has_more = ($offset + $limit) < $totalCount;
        return [
            'status' => 'success',
            'data' => $places,
            'cord' => $this->user->get_user_cordinates(),
            'user_account_id' => $this->user->get_user_account_id(),
            'has_more' => $has_more
        ];
    }
    public function get_places_category($data){
        $offset = isset($data['offset']) ? (int)$data['offset'] : 0;
        $limit = isset($data['limit']) ? (int)$data['limit'] : 20;
        $search = isset($data['search']) ? trim($data['search']) : '';
        $categories = $this->place_model->get_categories($offset, $limit, $search);
        $totalCount = $this->place_model->count_categories($search);
        $has_more = ($offset + $limit) < $totalCount;
        return [
            'status' => 'success',
            'data' => $categories,
            'has_more' => $has_more
        ];
    }
    public function add_place($data){
        $id = isset($data['id']) ? (int)$data['id'] : null;
        if ($id) {
            $updated = $this->place_model->update_place($id, $data);
            if ($updated) {
                return ['status' => 'success', 'id' => $id];
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