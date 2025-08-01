<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News_handler
{
    private Security_handler $security;
    private User_handler $user;
    private Functions_handler $function;
    private Category_model $category_model;
    private News_model $news_model;
    private Notification_model $notification_model;
    private Users_model $users_model;
    private Media_model $media_model;
    private Report_model $report_model;
    public function __construct(
        Security_handler $security_handler,
        User_handler $user_handler,
        Functions_handler $functions_handler,
        Category_model $category_model,
        News_model $news_model,
        Notification_model $notification_model,
        Users_model $users_model,
        Media_model $media_model,
        Report_model $report_model
    ){
        $this->security = $security_handler;
        $this->user = $user_handler;
        $this->function = $functions_handler;
        $this->category_model = $category_model;
        $this->news_model = $news_model;
        $this->notification_model = $notification_model;
        $this->users_model = $users_model;
        $this->media_model = $media_model;
        $this->report_model = $report_model;
	}
    public function get_news($data){
        if(!empty($data) && !empty($this->user->get_user_account_id()) && intval($this->user->get_user_account_id())>0){
            $limited=intval($data['limit']??10);
            $offset=intval($data['offset']??0);
            $userCoordinate = $this->user->get_user_cordinates();
            $data=$this->users_model->get_full_user_data_by_account_id(intval($this->user->get_user_account_id()));
            $this->news_model->setUserLocation(
                intval($this->user->get_user_account_id()),
                intval($data['rules']['0']['rule_id']??0),
                floatval($userCoordinate['lat']??null),
                floatval($userCoordinate['lon']??null));
            if($this->function->has_category_id()){
                $arr = $this->news_model->get_private_checking_news_by_category(
                    $this->user->get_user_category_id(),
                    $limited,
                    $offset);
            }else{
                $arr = $this->news_model->get_public_checking_news($limited,$offset);
            }
            // $this->function->set_data_user_location($arr['data']??[]);
            // $arr=array_reverse($arr);
            $arr['status']='success';
            $arr['rule']=$this->function->has_category_id();
            return $arr;
        }
        return ['status'=>'error'];
    }
    public function get_news_by_id($data){
        return (!empty($this->user->get_user_account_id()) && 
        intval($this->user->get_user_account_id())>0 && 
        !empty($data) && !empty($data['id']) && intval($data['id'])>0 ? [
            'status'=>'success',
            'data'=>$this->news_model->get_news_by_id(
                intval($data['id']),
                intval($this->user->get_user_account_id())
            ),
            'rule'=>$this->function->has_category_id()
        ]:['status'=>'error']);
    }
    public function add_news($data){
        if(!empty($data) && $this->user->get_user_account_id() && 
        !empty($data['category_id']) && 
        !empty($data['description']) && 
        !empty($data['user_address']) && 
        !empty($data['user_address']['type'])){
            $category = array_map('intval', $data['category_id']);
            $change_address=true;
            if($data['user_address']['type']==='location' && !empty($data['user_address']['value']))
                if(!empty($data['user_address']['value']['total']))
                    $address_id=$this->users_model->add_address_return_id([
                        'address'=> $this->security->string_secutory_week_check($data['user_address']['value']['total']['display_name']??''),
                        'country'=>$data['user_address']['value']['total']['address']['country']??'',
                        'region'=>$data['user_address']['value']['total']['address']['province']??'',
                        'city'=>$data['user_address']['value']['total']['address']['city']??$data['user_address']['value']['total']['address']['town']??$data['user_address']['value']['total']['address']['village']??'',
                        'lat'=>$data['user_address']['value']['total']['lat']??'',
                        'lon'=>$data['user_address']['value']['total']['lon']??'',
                        'code_posti'=>$data['user_address']['value']['total']['address']['postcode']??'',
                    ]);
                else
                    if(!empty($data['user_address']['value']['address_id']) && intval($data['user_address']['value']['address_id'])>0){
                        $change_address=false;
                        $address_id=intval($data['user_address']['value']['address_id']);
                    }else{
                        return ['status'=>'error','msg'=>'5'];
                    }
            else
                $address_id=$this->user->get_user_address_id();
            if(!(!empty($address_id) && intval($address_id)>0)) return ['status'=>'error','msg'=>'2','id'=>$address_id];
            if(!empty($data['edit']) && intval($data['edit'])>0){
                if(!empty($data['edit_report']) && intval($data['edit_report'])>0){
                    $this->report_model->edit_report_weher_id(['description'=>$this->security->string_secutory_week_check($data['description'])],intval($data['edit_report']));
                    $old_media_ids=$new_media_ids=[];
                    $old_medias=$this->media_model->select_relation_where_array(['target_table'=>'report_list','target_id'=>intval($data['edit_report'])]);
                    if(!empty($old_medias)) $old_media_ids=array_column($old_medias, 'media_id');
                    if(!empty($data['media_id'])) $new_media_ids=$data['media_id'];
                    $this->media_model->check_changes('report_list',intval($data['edit_report']),$old_media_ids,$new_media_ids);
                    if($change_address){
                        $old_address=$this->users_model->select_address_relation_where_report_id(intval($data['edit_report']));
                        if(!empty($old_address) && !empty(end($old_address)) && 
                        !empty(end($old_address)['id']) && intval(end($old_address)['id'])>0 &&
                        !empty(end($old_address)['address_id']) && intval(end($old_address)['address_id'])>0){
                            $this->users_model->edit_address_relation_weher_id([
                                'target_table'=>'user_account',
                                'target_id'=>$this->user->get_user_account_id()
                            ],intval(end($old_address)['id']));
                            $this->users_model->add_address_relation([
                                'target_table'=>'report_list',
                                'address_id'=>$address_id,
                                'target_id'=>intval($data['edit'])
                            ]);
                        }
                    }
                }else{
                    $this->news_model->change_description_where_id(intval($data['edit']),$this->security->string_secutory_week_check($data['description']));
                    $old_media_ids=$new_media_ids=[];
                    $old_medias=$this->media_model->select_relation_where_array(['target_table'=>'news','target_id'=>intval($data['edit'])]);
                    if(!empty($old_medias)) $old_media_ids=array_column($old_medias, 'media_id');
                    if(!empty($data['media_id'])) $new_media_ids=$data['media_id'];
                    $this->media_model->check_changes('news',intval($data['edit']),$old_media_ids,$new_media_ids);
                    $old_category=$this->category_model->select_relation_where_array(['target_table'=>'news','target_id'=>intval($data['edit'])]);
                    $this->category_model->check_changes('news',intval($data['edit']),$old_category,$category);
                    if($change_address){
                        $old_address=$this->users_model->select_address_relation_where_news_id(intval($data['edit']));
                        if(!empty($old_address) && !empty(end($old_address)) && 
                        !empty(end($old_address)['id']) && intval(end($old_address)['id'])>0 &&
                        !empty(end($old_address)['address_id']) && intval(end($old_address)['address_id'])>0){
                            $this->users_model->edit_address_relation_weher_id([
                                'target_table'=>'user_account',
                                'target_id'=>$this->user->get_user_account_id()
                            ],intval(end($old_address)['id']));
                            $this->users_model->add_address_relation([
                                'target_table'=>'news',
                                'address_id'=>$address_id,
                                'target_id'=>intval($data['edit'])
                            ]);
                        }
                    }
                }
                return ['status'=>'success','id'=>intval($data['edit'])];
            }else{
                if(!empty($data['reply_to_id']) && intval($data['reply_to_id'])>0){
                    $news_creator_user_account=$this->users_model->select_user_account_id_where_news_id(intval($data['reply_to_id']));
                    if($this->function->has_category_id() && 
                    intval($this->user->get_user_account_id())>0 && 
                    !empty($news_creator_user_account) && !empty(end($news_creator_user_account))){
                        $report_id=$this->report_model->add_report_return_id([
                            'news_id'=>intval($data['reply_to_id']),
                            'description'=>$this->security->string_secutory_week_check($data['description'])
                        ]);
                        if(!empty($report_id) && intval($report_id)>0){
                            $this->users_model->add_account_relations([
                                'user_account_id'=>intval($this->user->get_user_account_id()),
                                'target_table'=>'report_list',
                                'target_id'=>intval($report_id)
                            ]);
                            $this->users_model->add_address_relation([
                                'address_id'=>intval($address_id),
                                'target_table'=>'report_list',
                                'target_id'=>intval($report_id),
                            ]);
                            $this->news_model->seen_weher_id(intval($data['reply_to_id']));
                            if(!empty(end($news_creator_user_account)['user_account_id']) && 
                            intval(end($news_creator_user_account)['user_account_id'])>0){
                                $user_news_creator_notif_id=$this->notification_model->add_return_id([
                                    'title'=>'بررسی خبر',
                                    'body'=>'خبری که شما در سیستم قرار دادید در حال بررسی می باشد',
                                    'url'=>'/show-cartable/'.intval($report_id),
                                ]);
                                $this->users_model->add_account_relations([
                                    'user_account_id'=>intval(end($news_creator_user_account)['user_account_id']),
                                    'target_table'=>'notification',
                                    'target_id'=>intval($user_news_creator_notif_id)
                                ]);
                            }
                            $user_report_creator_notif_id=$this->notification_model->add_return_id([
                                'title'=>'بررسی جدید',
                                'body'=>'شما یک خبر جدید را به لیست خود اضافه کردید',
                                'url'=>'/show-cartable/'.intval($report_id),
                            ]);
                            $this->users_model->add_account_relations([
                                'user_account_id'=>intval($this->user->get_user_account_id()),
                                'target_table'=>'notification',
                                'target_id'=>intval($user_report_creator_notif_id)
                            ]);
                            if(!empty($data['media_id'])){
                                $this->media_model->change_used_status_where_array_ids($data['media_id']);
                                $this->media_model->add_relation_batch(array_map(function($id) use ($report_id) {
                                    return [
                                        'media_id' => $id, 
                                        'target_table' => 'report_list',
                                        'target_id'=>intval($report_id)
                                    ];
                                }, $data['media_id']));
                            }
                            
                            return ['status'=>'success','id'=>intval($data['reply_to_id'])];
                        }else{
                            return ['status'=>'error','msg'=>'4'];
                        }
                    }else{
                        return ['status'=>'error','msg'=>'3'];
                    }
                }else{
                    $news_id=$this->news_model->add_return_id([
                        'privacy' => ($this->user->get_user_category_id()?'public':'private'),
                        'description'=>$this->security->string_secutory_week_check($data['description'])
                    ]);
                    if(!empty($news_id) && intval($news_id)>0){
                        $this->users_model->add_account_relations([
                            'user_account_id'=>intval($this->user->get_user_account_id()),
                            'target_table'=>'news',
                            'target_id'=>intval($news_id)
                        ]);
                        $this->users_model->add_address_relation([
                            'address_id'=>intval($address_id),
                            'target_table'=>'news',
                            'target_id'=>intval($news_id),
                        ]);
                        $this->category_model->insert_relation_batch(array_map(function($id) use ($news_id) {
                            return [
                                'target_id' => intval($news_id),
                                'target_table'=>'news',
                                'category_id' => intval($id)
                            ];
                        },$category));
                        $this->function->send_add_news_notification($category,intval($news_id));
                        if(!empty($data['media_id'])){
                            $this->media_model->change_used_status_where_array_ids($data['media_id']);
                            $this->media_model->add_relation_batch(array_map(function($id) use ($news_id) {
                                return [
                                    'media_id' => $id, 
                                    'target_table' => 'news',
                                    'target_id'=>intval($news_id)
                                ];
                            }, $data['media_id']));
                        }
                        return ['status'=>'success','id'=>intval($news_id)];
                    }else{
                        return ['status'=>'error','msg'=>'2'];
                    }
                }
            }
        }else{
            return ['status'=>'error','msg'=>'3'];
        }
    }
    public function add_news_to_list($data){
        if(!empty($data) &&
        !empty($data['news_id']) && intval($data['news_id'])>0 &&
        ($a=$this->users_model->select_user_account_id_where_news_id(intval($data['news_id'])))!==false &&
        !empty($a) && !empty(end($a)) && 
        $this->function->has_category_id() && 
        intval($this->user->get_user_account_id())>0){
            if(!empty($data['report_id']) && intval($data['report_id'])>0){
                $id=intval($data['report_id']);
                $this->report_model->edit_report_weher_id(['run_time'=>$data['run_time']??null],$id);
            }else{
                $id=$this->report_model->add_report_return_id([
                    'news_id'=>intval($data['news_id']),
                    'run_time'=>$data['run_time']??null,
                ]);
                if(!empty($id) && intval($id)>0){
                    $this->users_model->add_account_relations([
                        'user_account_id'=>intval($this->user->get_user_account_id()),
                        'target_table'=>'report_list',
                        'target_id'=>intval($id)
                    ]);
                }else{
                    return ['status'=>'error','type'=>1];    
                }
            }
            if($this->news_model->seen_weher_id(intval($data['news_id']))){
                if(!empty(end($a)['user_account_id']) && intval(end($a)['user_account_id'])>0){
                    $user_notif_id=$this->notification_model->add_return_id([
                        'title'=>'بررسی خبر',
                        'body'=>'خبری که شما در سیستم قرار دادید در حال بررسی می باشد',
                        'url'=>'/show-cartable/'.intval($id),
                    ]);
                    $this->users_model->add_account_relations([
                        'user_account_id'=>intval(end($a)['user_account_id']),
                        'target_table'=>'notification',
                        'target_id'=>intval($user_notif_id)
                    ]);
                }
                $user_notif_id=$this->notification_model->add_return_id([
                    'title'=>'بررسی جدید',
                    'body'=>'شما یک خبر جدید را به لیست خود اضافه کردید',
                    'url'=>'/show-cartable/'.intval($id),
                ]);
                $this->users_model->add_account_relations([
                    'user_account_id'=>intval($this->user->get_user_account_id()),
                    'target_table'=>'notification',
                    'target_id'=>intval($user_notif_id)
                ]);
                return ['status'=>'success'];
            }
        }
        return ['status'=>'error' ];    
    }
    public function add_data(){
        $this->function->category_filtter_for_place=true;
        $this->function->get_all_category_active();
        if($this->user->get_user_account_id()){
            return [
                'status'=>'success',
                'rule'=>($this->function->has_category_id()?true:false),
                'address'=>$this->user->get_user_location(),
                'coordinate'=>$this->user->get_user_cordinates(),
                'category'=>($this->function->has_category_id()?$this->function->search_id_return_value_in_key($this->function->category,intval($this->user->get_user_category_id()),'id',['id','title']):$this->function->category),
            ];
        }
        return ['status'=>'error'];
    }
    public function user_news(){
        return (!empty($this->user->get_user_account_id()) && intval($this->user->get_user_account_id())>0?
        ['status'=>'success','data'=>$this->news_model->get_news_by_user_account_id(intval($this->user->get_user_account_id()))]:
        ['status'=>'error']);
    }
    public function get_news_for_month(){
        return (!empty($this->user->get_user_account_id()) && intval($this->user->get_user_account_id())>0?
        ['status'=>'success','data'=>$this->news_model->get_all_runtime_reports_by_user(intval($this->user->get_user_account_id()))]:
        ['status'=>'error']);
    }
    public function delete_news($data){
        return (!empty($data) && !empty($data['id']) && intval($data['id'])>0 && ($a=$this->user->get_user_account_id())!==false && !empty($a) && intval($a)>0 && $this->news_model->seen_weher_id_and_user_account_id(intval($data['id']))?
        ['status'=>'success']:
        ['status'=>'error']);
    }
    public function restore_news($data){
        return (!empty($data) && !empty($data['id']) && intval($data['id'])>0 && ($a=$this->user->get_user_account_id())!==false && !empty($a) && intval($a)>0 && $this->news_model->checking_weher_id_and_user_account_id(intval($data['id']))?
        ['status'=>'success']:
        ['status'=>'error']);
    }
    public function get_cartables($data){
        return (!empty($data) && !empty($this->user->get_user_account_id()) && intval($this->user->get_user_account_id())>0 &&
        ($a=$this->news_model->get_user_reports_with_news($this->user->get_user_account_id(),intval($data['limit']??10),intval($data['offset']??0)))!==false?
        ['status'=>'success',
        'data'=>$a['data']??[],
        'has_more'=>$a['has_more']??false,
        'rule'=>(!empty($this->function->has_category_id()))]:
        ['status'=>'error']);
    }
    public function get_cartable_by_id($data){
        return (!empty($data) && !empty($data['id']) && intval($data['id'])>0 && !empty($this->user->get_user_account_id()) && intval($this->user->get_user_account_id())>0?
        ['status'=>'success','data'=>$this->news_model->get_report_with_news_by_report_id(intval($data['id']),intval($this->user->get_user_account_id())),'rule'=>(!empty($this->function->has_category_id()))]:
        ['status'=>'error']);
    }
    public function edit_report($data){
        if (!empty($data) && !empty($data['id']) && intval($data['id'])>0){
            $media=$data['media_id']??[];
            $this->media_model->change_used_status_where_array_ids($media);
            $this->report_model->edit_report_weher_id([
                'description'=>$data['description']??''
            ],intval($data['id']));
            $this->media_model->edit_medias_and_relations('report_list',intval($data['id']),$media);
            return ['status'=>'success','data'=>$data];
        }
        return ['status'=>'error'];
    }
}
    // private function group_reports_by_news(array $flat_reports) {
    //     $grouped = [];

    //     foreach ($flat_reports as $item) {
    //         $news_id = $item['news_id'];

    //         if (!isset($grouped[$news_id])) {
    //             $grouped[$news_id] = [
    //                 'news' => [
    //                     'id' => $news_id,
    //                     'title' => $item['title'] ?? null,
    //                     'description' => $item['news_description'] ?? $item['description'] ?? null,
    //                     'status' => $item['news_status'] ?? $item['status'] ?? null,
    //                     'privacy' => $item['news_privacy'] ?? null,
    //                     'user_account_id' => $item['user_account_id'] ?? $this->user->get_user_account_id(),
    //                     'user_name' => $item['user_name'] ?? null,
    //                     'user_family' => $item['user_family'] ?? null,
    //                     'user_phone' => $item['user_phone'] ?? null,
    //                     'user_image_url' => $item['user_image_url'] ?? null,
    //                     'address' => $item['address'] ?? [],
    //                     'category' => $item['category'] ?? [],
    //                     'news_media' => $item['news_media'] ?? [],
    //                 ],
    //                 'report_list' => [],
    //             ];
    //         }

    //         $grouped[$news_id]['report_list'][] = [
    //             'id' => $item['report_id'] ?? $item['id'],
    //             'description' => $item['report_description'] ?? $item['description'] ?? null,
    //             'status' => $item['status'] ?? null,
    //             'run_time' => $item['run_time'] ?? null,
    //             'created_at' => $item['created_at'] ?? null,
    //             'updated_at' => $item['updated_at'] ?? null,
    //             'report_media' => $item['report_media'] ?? [],
    //             'reporter' => [
    //                 'user_account_id' => $item['reporter']['user_account_id'] ?? null,
    //                 'name' => $item['reporter']['name'] ?? null,
    //                 'family' => $item['reporter']['family'] ?? null,
    //                 'phone' => $item['reporter']['phone'] ?? null,
    //                 'user_image_url' => $item['reporter']['user_image_url'] ?? null,
    //             ],
    //         ];
    //     }

    //     return array_values($grouped);
    // }
// $my_report=$this->report_model->get_reports_by_user_account_id($this->user->get_user_account_id());
            // $my_news=$this->report_model->get_reports_by_news_user_account_id($this->user->get_user_account_id());
            // $arr=$this->group_reports_by_news($my_news)+$my_report;
            // foreach ($arr as &$newsBlock) {
            //     foreach ($newsBlock['report_list'] as &$report) {
            //         $start = new DateTime($report['run_time'] ?? 'now');
            //         $status = $report['status'] ?? 'unknown';
            //         $end = $status === 'done'
            //             ? new DateTime($report['updated_at'] ?? 'now')
            //             : (($start > new DateTime()) ? (clone $start)->modify('+2 hours') : new DateTime());

            //         $report['start'] = $start->format(DateTime::ATOM);
            //         $report['end'] = $end->format(DateTime::ATOM);
            //     }
            // }
// $my_news=$this->report_model->get_reports_by_news_user_account_id($this->user->get_user_account_id());