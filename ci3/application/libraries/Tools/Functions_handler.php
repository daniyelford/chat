<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Functions_handler
{
    public $category=[];
    public $category_filtter_for_place=false;
    // public $media=[];
    // public $report_media=[];
    // public $product_media=[];
    // public $address=[];
    // public $all_news=[];
    // public $cartables=[];
    // public $news_manager=[];
    // public $result_cartables=[];
    private Wallet_model $wallet_model;
    private Media_model $media_model;
    private News_model $news_model;
    private User_handler $user;
    private Category_model $category_model;
    private Users_model $users_model;
    private Notification_model $notification_model;
    private Rule_model $rule_model;
    private Send_handler $send_handler;
    public function __construct(
        User_handler $user_handler,
        Send_handler $send_handler,
        Wallet_model $wallet_model,
        Category_model $category_model,
        Media_model $media_model,
        Users_model $users_model,
        Notification_model $notification_model,
        News_model $news_model,
        Rule_model $rule_model,
    ){
        $this->user = $user_handler;
        $this->send_handler = $send_handler;
        $this->wallet_model = $wallet_model;
        $this->category_model = $category_model;
        $this->media_model = $media_model;
        $this->users_model = $users_model;
        $this->notification_model = $notification_model;
        $this->news_model = $news_model;
        $this->rule_model=$rule_model;
	}
    public function has_category_id(){
        return (!empty($this->user->get_user_category_id()) && intval($this->user->get_user_category_id())>0);
    }
    public function get_all_category_active(){
        if($this->category_filtter_for_place)
            $this->category=$this->category_model->select_category_where_active_and_not_place();
        else
            $this->category = $this->category_model->select_category_where_active();
    }
    public function send_add_news_notification($category,$news_id){
        if(!empty($category) && is_array($category)){
            $a=$this->rule_model->get_users_rule_in_category($category);
            $rule_notif_id=$this->notification_model->add_return_id([
                'title'=>'گزارش کاربران',
                'body'=>'یک خبر جدید برای سازمان شما توسط کاربران ثبت شده است',
                'url'=>'/show-news/'.intval($news_id)
            ]);
            $user_notif_id=$this->notification_model->add_return_id([
                'title'=>'ثبت گزارش',
                'body'=>'گزارش شما برای سازمان مورد نظر ارسال شد',
                'url'=>'/show-news/'.intval($news_id)
            ]);
            $this->users_model->add_account_relations([
                'user_account_id'=>intval($this->user->get_user_account_id()),
                'target_table'=>'notification',
                'target_id'=>intval($user_notif_id)
            ]);
            $arr=[];
            if(!empty($a))
                foreach ($a as $b) {
                    if(!empty($b) && !empty($b['id']) && intval($b['id'])>0 && intval($b['id'])!==$this->user->get_user_account_id())
                        $arr[]=[
                            'user_account_id'=>intval($b['id']),
                            'target_table'=>'notification',
                            'target_id'=>intval($rule_notif_id)
                        ];
                }
            if(!empty($arr)) $this->users_model->add_all_account_relations($arr);
        }
        $token=$this->rule_model->force_action($category,$news_id);
        if(!empty($token))
            foreach ($token as $t) {
                if(!empty($t))
                    $this->send_handler->send_notification('هشدار خبر فوری','برای اطلاع بیشتر از اخبار به آدرس '.base_url('show-news/'.intval($news_id)).' بروید',json_decode($t));
            }
        return true;
    }
    public function search_array($data,$key,$value){
        $result=[];
        if(!empty($data) && !empty($key) && !empty($value)){
            $result=$data[array_search($value, array_column($data, $key))];
        }
        return $result;
    }
    public function search_id_return_value_in_key($data,$id,$key,$key_array){
        $a = (!empty($data) && !empty($id) && !empty($key)?$this->search_array($data,$key,$id):'');
        if(!empty($key_array) && !empty($a)){
            if(is_string($key_array) && !empty($a[$key_array])) return $a[$key_array];
            $res=[];
            if(is_array($key_array))
                foreach($key_array as $k){
                    if(!empty($k) && !empty($a[$k]))
                        $res[$k]=$a[$k];
                }
            if(!empty($res)) return $res;
        }
        return '';
    }
}
    // public function get_all_media_used_news(){
    //     $this->media = $this->media_model->select_where_news_used();
    // }
    // public function get_all_media_used_report(){
    //     $this->report_media = $this->media_model->select_where_report_used();
    // }
    // public function get_all_category_news_relation(){
    //     $this->category_news=$this->category_model->select_all_relation();
    // }
    // public function get_all_address_news(){
    //     $this->address = $this->users_model->select_address_where_news();
    // }
    // public function get_all_my_news(){
    //     $this->news_manager=$this->news_model->select_news_where_user_account_id($this->user->get_user_account_id());
    // }
    // public function get_all_news(){
    //     $this->all_news=$this->news_model->select_news();
    // }
    // public function haversine_distance($lat1, $lon1, $lat2, $lon2) {
    //     $earth_radius = 6371;
    //     $dLat = deg2rad($lat2 - $lat1);
    //     $dLon = deg2rad($lon2 - $lon1);
    //     $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    //     $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    //     return $earth_radius * $c;
    // }
    // public function set_data_user_location($arr){
    //     if(($userLocation = $this->user->get_user_location()) !== false && $userLocation && 
    //     ($userCoordinate = $this->user->get_user_cordinates())!==false && $userCoordinate &&
    //     !empty($arr)){
    //         $user_lat = $userCoordinate['lat'];
    //         $user_lon = $userCoordinate['lon'];
    //         usort($arr, function($a, $b) use ($userLocation, $user_lat, $user_lon) {
    //             $a_city = $a['city'] ?? '';
    //             $b_city = $b['city'] ?? '';
    //             $a_lat = $a['address_lat'] ?? 0;
    //             $a_lon = $a['address_lon'] ?? 0;
    //             $b_lat = $b['address_lat'] ?? 0;
    //             $b_lon = $b['address_lon'] ?? 0;
    //             $aMatch = ($a_city === $userLocation) ? 0 : 1;
    //             $bMatch = ($b_city === $userLocation) ? 0 : 1;
    //             if ($aMatch !== $bMatch) return $aMatch - $bMatch;
    //             $distanceA = $this->haversine_distance($user_lat, $user_lon, $a_lat, $a_lon);
    //             $distanceB = $this->haversine_distance($user_lat, $user_lon, $b_lat, $b_lon);
    //             return $distanceA <=> $distanceB;
    //         });
    //     }
    // }

    // public function search_ids_return_value_in_key($data,$ids,$key,$key_wanted){
    //     $ids=(!empty($ids)?explode(',',$ids):[]);
    //     return $this->search_ids_array_return_value_in_key($data,$ids,$key,$key_wanted);
    // }
    // public function search_ids_array_return_value_in_key($data,$ids,$key,$key_wanted){
    //     $result=[];
    //     if(!empty($data) && !empty($ids) && !empty($key) && !empty($key_wanted))
    //         foreach ($ids as $id) {
    //             if(!empty($id) && intval($id)>0) $result[]=$this->search_id_return_value_in_key($data,intval($id),$key,$key_wanted);
    //         }
    //     return (!empty($key_wanted) && is_string($key_wanted)?implode(',',$result):$result);
    // }

    // public function get_cartables_data(){
    //     $this->get_all_category_active();
    //     $this->get_all_category_news_relation();
    //     $this->get_all_media_used_news();
    //     $this->get_all_media_used_report();
    //     $this->get_all_address_news();
    //     $this->get_all_news();
    //     $this->get_all_my_news();
    //     $my_seenIds = array_column($this->news_manager, 'id');
    //     if(!empty($my_seenIds))
    //         $this->cartables=$this->news_model->get_reports_by_account_or_news_ids($this->user->get_user_account_id(),$my_seenIds);
    //     else
    //         $this->cartables=$this->news_model->select_report_where_user_account_id($this->user->get_user_account_id());
    // }
    // public function set_cartables_data(){
    //     if(!empty($this->cartables))
    //         foreach($this->cartables as $a){
    //             if(!empty($a) && !empty($a['id']) && intval($a['id'])>0 && !empty($a['news_id']) && intval($a['news_id'])>0 && !empty($a['user_account_id']) && intval($a['user_account_id'])>0){
    //                 $news=$this->search_array($this->all_news,'id',intval($a['news_id']));
    //                 if(!empty($news) && !empty($news['user_account_id']) && intval($news['user_account_id'])>0){
    //                     $arr=[];
    //                     $arr['id']=intval($a['id']);
    //                     if($this->has_category_id() && intval($a['user_account_id'])===intval($this->user->get_user_account_id())){
    //                         $arr['user']=$this->user->get_user_info_where_user_account(intval($news['user_account_id']));
    //                         $arr['has_rule']=true;
    //                     }else{
    //                         $arr['user']=$this->user->get_user_info_where_user_account(intval($a['user_account_id']));
    //                         $arr['has_rule']=false;
    //                     }
    //                     $report_result=$news_result=[];
    //                     $news_result['id']=$news['id']??'';
    //                     $news_result['description']=$news['description']??'';
    //                     $news_result['media']=$this->search_ids_return_value_in_key($this->media,$news['media_id']??'','id',['url','type']);
    //                     $news_result['address']=$this->search_id_return_value_in_key($this->address,$news['user_address_id']??0,'id',['id','city','lat','lon','address']);
    //                     $report_result['description']=$a['description']??'';
    //                     $report_result['media']=$this->search_ids_return_value_in_key($this->report_media,$a['media_id']??'','id',['id','url','type']);
    //                     $report_result['run_time']=$a['run_time']??'';
    //                     $report_result['created_at']=$a['created_at']??'';
    //                     $arr['report']=$report_result;
    //                     $arr['news']=$news_result;
    //                     $this->result_cartables[]=$arr;
    //                 }
    //             }
    //         }
    // }
    // public function find_order($ids){
    //     $result=[];
    //     if(!empty($ids) && is_string($ids) && ($a=explode(',',$ids))!==false &&
    //     !empty($a) && ($b=$this->wallet_model->select_orders_where_in_order_ids($a))!==false && !empty($b))
    //         foreach ($b as $c) {
    //             if(!empty($c) && !empty($c['product_id']) && intval($c['product_id'])>0){
    //             $arr=[];  
    //             $arr['total_price']=$c['amount']??0;
    //             $arr['product_count']=$c['product_count']??1;
    //             $arr['created_at']=$c['created_at']??'';
    //             $arr['updated_at']=$c['updated_at']??'';
    //             $arr['report']=$this->find_report_info($c['report_list_id']??0);
    //             $arr['product_info']=$this->find_product_info(intval($c['product_id']));
    //             $result[]=$arr;
    //             }
    //         }
    //     return $result;
    // }
    // public function find_product_info($id){
    //     $result=[];
    //     if(!empty($id) && intval($id)>0 && 
    //     ($a=$this->wallet_model->select_product_where_id(intval($id)))!==false &&
    //     !empty($a) && !empty(end($a))){
    //         $result=end($a);
    //         $result['media']=$this->search_ids_return_value_in_key($this->product_media,end($a)['media_id']??'','id',['url','type']);
    //     }
    //     return $result;
    // }
    // public function find_report_info($id){
    //     return (!empty($id) && intval($id)>0?$this->search_array($this->result_cartables,'id',intval($id)):[]);
    // }
    // public $news=[];
    // public $report=[];
    // public $news_seen=[];
    // public $result=[];
    // public $result_manager=[];
    // public $result_report=[];
    // public function get_all_media_used_product(){
    //     $this->product_media = $this->media_model->select_where_product_used();
    // }
    // public function get_all_news_seen(){
    //     $this->news_seen=$this->news_model->select_news_where_status_seen();
    // }
    // public function get_all_category_array_where_news_id($id) {
    //     $id = (int) $id;
    //     if (empty($this->category_news) || $id <= 0) return [];
    //     return array_values(array_map(
    //         fn($a) => (int) $a['category_id'],
    //         array_filter($this->category_news, function ($a) use ($id) {
    //             return !empty($a['category_id']) && !empty($a['news_id']) &&
    //                 (int) $a['news_id'] === $id && (int) $a['category_id'] > 0;
    //         })
    //     ));
    // }
    // public function get_all_report_where_news_id($id){
    //     return (!empty($id) && intval($id)>0 && ($a=$this->news_model->select_report_where_news_id(intval($id)))!==false && !empty($a)?$a:null);
    // }
    // public function get_all_my_report(){
    //     $this->get_all_my_news();
    //     $my_seenIds = array_column(array_filter($this->news_manager, function($item) {
    //         return isset($item['status']) && $item['status'] === 'seen';
    //     }), 'id');
    //     if(!empty($my_seenIds))
    //         $this->report=$this->news_model->get_reports_by_account_or_news_ids($this->user->get_user_account_id(),$my_seenIds);
    //     else
    //         $this->report=$this->news_model->select_report_where_user_account_id($this->user->get_user_account_id());
    // }
    // public function set_data(){
    //     if(!empty($this->news))
    //         foreach ($this->news as $a) {
    //             if(!empty($a) && !empty($a['id']) && intval($a['id'])>0 && 
    //             !empty($a['user_account_id']) && intval($a['user_account_id'])>0 && 
    //             intval($a['user_account_id'])!==intval($this->user->get_user_account_id())){
    //                 $location=$this->search_id_return_value_in_key($this->address,$a['user_address_id']??0,'id',['city','lat','lon']);
    //                 $arr=[];
    //                 $arr['id']=$a['id']??'';
    //                 $arr['created_at']=$a['created_at']??'';
    //                 $arr['description']=$a['description']??'';
    //                 $arr['media']=$this->search_ids_return_value_in_key($this->media,$a['media_id']??'','id',['id','url','type']);
    //                 $arr['category']=$this->search_ids_array_return_value_in_key($this->category,$this->get_all_category_array_where_news_id(intval($a['id'])),'id','title');
    //                 $arr['location']=$location['city']??'';
    //                 $user=$this->user->get_user_info_where_user_account(intval($a['user_account_id']));
    //                 array_pop($user);
    //                 $arr['user']=$user;
    //                 $arr['total_location']=$location??[];
    //                 $this->result[]=$arr;
    //             }
    //         }
    // }

    // public function get_manager_data(){
    //     $this->get_all_category_active();
    //     $this->get_all_category_news_relation();
    //     $this->get_all_media_used_news();
    //     $this->get_all_address_news();
    //     $this->get_all_my_news();
    // }
    // public function set_manager_data(){
    //     if(!empty($this->news_manager))
    //         foreach ($this->news_manager as $a) {
    //             if(!empty($a) && !empty($a['id']) && intval($a['id'])>0){
    //                 $arr=[];
    //                 $arr['id']=intval($a['id']);
    //                 $arr['address']=$this->search_id_return_value_in_key($this->address,$a['user_address_id']??0,'id',['id','city','lat','lon','address']);
    //                 $arr['category']=$this->search_ids_array_return_value_in_key($this->category,$this->get_all_category_array_where_news_id(intval($a['id'])),'id',['id','title']);
    //                 $arr['media']=$this->search_ids_return_value_in_key($this->media,$a['media_id']??'','id',['id','url','type']);
    //                 $arr['description']=$a['description']??'';
    //                 $arr['status']=$a['status']??'';
    //                 $a=$this->get_all_report_where_news_id(intval($a['id']));
    //                 $arr['report']=(!empty($a));
    //                 $arr['reportList']=(!empty($a)?$a:[]);
    //                 $this->result_manager[]=$arr;
    //             }
    //         }
    // }
    // public function set_all_my_report(){
    //     if(!empty($this->report))
    //         foreach ($this->report as $a) {
    //             if(!empty($a) && !empty($a['id']) && intval($a['id'])>0 && !empty($a['user_account_id']) && intval($a['user_account_id'])>0){
    //                 $arr=[];
    //                 $status=$a['status']??'';
    //                 $start = new DateTime($a['run_time'] ?? date('Y-m-d H:i:s'));
    //                 $arr['start'] = $start->format(DateTime::ATOM);
    //                 if ($status === 'done') {
    //                     $arr['end'] = (new DateTime($a['updated_at'] ?? date('Y-m-d H:i:s')))->format(DateTime::ATOM);
    //                 } else {
    //                     $now = new DateTime();
    //                     $arr['end'] = ($start > $now)
    //                         ? (clone $start)->modify('+2 hours')->format(DateTime::ATOM)
    //                         : $now->format(DateTime::ATOM);
    //                 }
    //                 $arr['me']=(intval($a['user_account_id'])!==intval($this->user->get_user_account_id()));
    //                 $news_result=[];
    //                 $news=$this->search_id_return_value_in_key($this->all_news,$a['news_id']??'','id',['id','user_account_id','user_address_id','media_id','description']);
    //                 if(!empty($news) && !empty($news['id']) && intval($news['id'])>0){
    //                     $news_result['id']=$news['id'];
    //                     $news_result['description']=$news['description']??'';
    //                     $news_result['media']=$this->search_ids_return_value_in_key($this->media,$news['media_id']??'','id',['url','type']);
    //                     $news_result['address']=$this->search_id_return_value_in_key($this->address,$news['user_address_id']??0,'id',['id','city','lat','lon','address']);
    //                     if(!empty($news['user_account_id']) && intval($news['user_account_id'])>0){
    //                         $news_result['user']=$this->user->get_user_info_where_user_account(intval($news['user_account_id']));
    //                     }
    //                 }
    //                 $arr['news']=$news_result;
    //                 $this->result_report[]=$arr;
    //             }
    //         }
    // }