<?php


class News_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
	}
	private $tbl="news";
    private $report="report_list";
    private ?float $user_lat = null;
    private ?float $user_lon = null;
    private ?int $user_account_id = 0;
    private ?int $rule_id = 0;
    private ?bool $just_run_time_report = false;
    private ?bool $just_news = false;
    private function select_where_array_table($tbl,$arr){
	    return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr)?$this->db->get_where($tbl,$arr)->result_array():false);
	}
    private function add_to_table_return_id($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert($tbl,$arr)?$this->db->insert_id():false);
    }
    private function edit_table($tbl,$arr,$where){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && !empty($where) && is_array($where) && $this->db->update($tbl,$arr,$where));
    }
    // costum
    // news
    // costum sql function
    private function apply_private_news_filters(array $category_ids): void {
        $this->db->flush_cache();
        $this->db->start_cache();
        $category_ids = array_filter($category_ids, fn($id) => is_numeric($id));
        if (empty($category_ids)) {
            $this->db->where('1', '0', false);
            return;
        }
        $this->db->distinct();
        $this->db->select('n.*');
        $this->db->from('news n');
        $this->db->join('category_relation cr', 'cr.target_id = n.id AND cr.target_table = \'news\'', 'left');
        if ($this->user_account_id > 0) {
            $this->db->join('user_account_relations uar',
             'uar.target_id = n.id AND uar.target_table = \'news\' AND uar.user_account_id = ' . intval($this->user_account_id), 'left');
            $this->db->group_start();
                $this->db->group_start();
                    $this->db->where_in('cr.category_id', $category_ids);
                    if($this->rule_id !== 1){
                        $this->db->where('n.privacy', 'private');
                    }
                $this->db->group_end();
                $this->db->or_where('uar.user_account_id IS NOT NULL', null, false);
            $this->db->group_end();
        } else {
            $this->db->where_in('cr.category_id', $category_ids);
            $this->db->where('n.privacy', 'private');
        }
        $this->db->where('n.show_status', 'do');
        $this->db->group_by('n.id');
        $this->db->stop_cache();
    }
    private function apply_public_news_filters(): void {
        $this->db->flush_cache();
        $this->db->start_cache();
        $this->db->distinct();
        $this->db->select('n.*');
        $this->db->from('news n');
        if ($this->user_account_id > 0) {
            $this->db->join(
                'user_account_relations uar',
                'uar.target_id = n.id AND uar.target_table = \'news\' AND uar.user_account_id = ' . intval($this->user_account_id),
                'left'
            );
            $this->db->group_start();
                $this->db->group_start();
                    $this->db->where('n.status', 'checking');
                    $this->db->where('n.privacy', 'public');
                $this->db->group_end();
                $this->db->or_where('uar.user_account_id IS NOT NULL', null, false);
            $this->db->group_end();
        } else {
            $this->db->where('n.status', 'checking');
            $this->db->where('n.privacy', 'public');
        }
        $this->db->where('n.show_status', 'do');
        $this->db->group_by('n.id');
        $this->db->stop_cache();
    }
    // costum sql function
    // costum relations function
    private function get_categories_for_target(string $target_table, array $target_ids): array {
        if (empty($target_ids)) return [];
        $this->db->select('cr.target_id, c.id AS category_id, c.title AS category_title');
        $this->db->distinct();
        $this->db->from('category_relation cr');
        $this->db->join('category c', 'c.id = cr.category_id');
        $this->db->where('cr.target_table', $target_table);
        $this->db->where_in('cr.target_id', $target_ids);
        $result = $this->db->get()->result();
        $map = [];
        foreach ($result as $row) {
            $category = [
                'id' => $row->category_id,
                'title' => $row->category_title,
            ];
            if (!in_array($category, $map[$row->target_id] ?? [])) {
                $map[$row->target_id][] = $category;
            }
        }
        return $map;
    }
    private function get_media_for_targets(string $target_table, array $target_ids): array {
        if (empty($target_ids)) return [];
        $this->db->select('mr.target_id,m.id AS media__id, m.url, m.type');
        $this->db->distinct();
        $this->db->from('media_relation mr');
        $this->db->join('media m', 'm.id = mr.media_id');
        $this->db->where('mr.target_table', $target_table);
        $this->db->where_in('mr.target_id', $target_ids);
        $results = $this->db->get()->result();
        $map = [];
        foreach ($results as $row) {
            $media = [
                'id' => $row->media__id,
                'url' => $row->url,
                'type' => $row->type,
            ];
            if (!in_array($media, $map[$row->target_id] ?? [])) {
                $map[$row->target_id][] = $media;
            }
        }
        return $map;
    }
    private function get_user_accounts_for_targets(string $target_table, array $target_ids): array {
        if (empty($target_ids)) return [];
        $this->db->select('uar.target_id,ua.id AS user_account_id,um.phone,u.name,u.family,m.url AS user_image_url');
        $this->db->distinct();
        $this->db->from('user_account_relations uar');
        $this->db->join('user_account ua', 'ua.id = uar.user_account_id');
        $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id');
        $this->db->join('users u', 'u.id = um.user_id');
        $this->db->join('media m', 'm.id = um.image_id', 'left');
        $this->db->where('uar.target_table', $target_table);
        $this->db->where_in('uar.target_id', $target_ids);
        $results = $this->db->get()->result();
        $map = [];
        foreach ($results as $row) {
            $map[$row->target_id] = [
                'self'=>$this->user_account_id==$row->user_account_id,
                'user_account_id' => $row->user_account_id,
                'phone' => $row->phone,
                'name'  => $row->name,
                'family' => $row->family,
                'user_image_url' => $row->user_image_url,
            ];
        }
        return $map;
    }
    private function get_addresses_for_targets(string $target_table, array $target_ids): array {
        if (empty($target_ids)) return [];
        $this->db->select('ar.target_id, a.city, a.address, a.lat, a.lon', false);
        if ($this->user_lat !== null && $this->user_lon !== null) {
            $this->db->select("
                (
                    6371 * acos(
                        cos(radians({$this->user_lat})) *
                        cos(radians(a.lat)) *
                        cos(radians(a.lon) - radians({$this->user_lon})) +
                        sin(radians({$this->user_lat})) *
                        sin(radians(a.lat))
                    )
                ) AS distance
            ", false);
        }
        $this->db->distinct();
        $this->db->from('address_relation ar');
        $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
        $this->db->where('ar.target_table', $target_table);
        $this->db->where_in('ar.target_id', $target_ids);
        $results = $this->db->get()->result();
        $map = [];
        foreach ($results as $row) {
            $map[$row->target_id] = [
                'address_id'      => $row->target_id,
                'city'    => $row->city,
                'address' => $row->address,
                'lat'     => $row->lat,
                'lon'     => $row->lon,
            ];
            if (isset($row->distance)) {
                $map[$row->target_id]['distance'] = floatval($row->distance);
            }
        }
        return $map;
    }
    // costum relations function
    // costum helper function
    protected function get_news_details_by_ids(array $news_rows): array {
        $news_ids = array_column($news_rows, 'id');
        if (empty($news_ids)) return [];
        $address_map = $this->get_addresses_for_targets('news', $news_ids);
        $user_map = $this->get_user_accounts_for_targets('news', $news_ids);
        $result = [];
        foreach ($news_rows as $item) {
            $id = $item['id'];
            $item = array_merge($item, $address_map[$id] ?? []);
            $item = array_merge($item, $user_map[$id] ?? []);
            $result[$id] = $item;
        }
        return $result;
    }
    protected function get_news_reports(array $news_ids): array {
        if (empty($news_ids)) return [];
        $this->db->select('rl.*');
        $this->db->distinct();
        $this->db->from('report_list rl');
        $this->db->where_in('rl.news_id', $news_ids);
        if($this->just_run_time_report){
            $this->db->where('rl.run_time IS NOT NULL', null, false);
        }
        $this->db->where('rl.show_status', 'do');
        $report_list = $this->db->get()->result_array();
        return $this->build_report_data($report_list);
    }
    // costum helper function
    // costum build function
    protected function build_news_data(array $news_rows): array {
        if (empty($news_rows)) return [];
        $news_ids = array_column($news_rows, 'id');
        $details_map = $this->get_news_details_by_ids($news_rows);
        $categories_map = $this->get_categories_for_target('news',$news_ids);
        $media_map = $this->get_media_for_targets('news', $news_ids);
        if(!$this->just_news){
            $reports_map = $this->get_news_reports($news_ids);
        }
        $result=[]; 
        foreach ($details_map as $item) {
            $arr=[];
            $id= $item['id'];
            $arr=$item;
            $arr['categories']   = $categories_map[$id] ?? [];
            $arr['media']        = $media_map[$id] ?? [];
            if(!$this->just_news){
                $arr['report_list']  = $reports_map[$id] ?? [];
            }
            $result[]=$arr;
        }
        return $result;
    }
    protected function build_report_data(array $report_list): array{
        $report_ids = array_column($report_list, 'id');
        if (empty($report_ids)) return [];
        $address_map = $this->get_addresses_for_targets('report_list', $report_ids);
        $media_map     = $this->get_media_for_targets('report_list', $report_ids);
        $reporters_map = $this->get_user_accounts_for_targets('report_list', $report_ids);
        $result = [];
        foreach ($report_list as $r) {
            $addr = $address_map[$r['id']] ?? [];
            $result[$r['news_id']][] = [
                'id' => $r['id'],
                'report_info' => $r,
                'location' => [
                    'address_id'=> $addr['address_id']    ?? null,
                    'city'    => $addr['city']    ?? null,
                    'address' => $addr['address'] ?? null,
                    'lat'     => $addr['lat']     ?? null,
                    'lon'     => $addr['lon']     ?? null,
                ],
                'report_media' => $media_map[$r['id']] ?? [],
                'reporter'     => $reporters_map[$r['id']] ?? [],
            ];
        }
        return $result;
    }
    // costum build function
    public function setUserLocation(?int $user_account_id,?int $rule_id,?float $lat, ?float $lon): void {
        $this->user_account_id = $user_account_id;
        $this->rule_id = $rule_id;
        $this->user_lat = $lat;
        $this->user_lon = $lon;
    }
    public function get_private_checking_news_by_category(array $category_ids, int $limit = 10, int $offset = 0): array {
        $this->apply_private_news_filters($category_ids);
        $this->db->order_by('n.id', 'DESC');
        $this->db->limit($limit+1, $offset);
        $news_rows = $this->db->get()->result_array();
        $this->db->flush_cache();
        if (empty($news_rows)) {
            return ['data' => [], 'has_more' => false];
        }
        $has_more = count($news_rows) > $limit;
        $news_rows = array_slice($news_rows, 0, $limit);
        $news_data = $this->build_news_data($news_rows);
        return ['data' => $news_data, 'has_more' => $has_more,'type'=>1];
    }
    public function get_public_checking_news(int $limit = 10, int $offset = 0): array {
        $this->apply_public_news_filters();
        $this->db->order_by('n.id', 'DESC');
        $this->db->limit($limit+1, $offset);
        $news_rows =$this->db->get()->result_array();
        $this->db->flush_cache();
        if (empty($news_rows)) {
            return ['data' => [], 'has_more' => false];
        }
        $has_more = count($news_rows) > $limit;
        $news_rows = array_slice($news_rows, 0, $limit);
        $news_data = $this->build_news_data($news_rows);
        return ['data' => $news_data, 'has_more' => $has_more,'type'=>2];
    }
    public function get_news_by_id(int $id,int $user_account_id) {
        $news=$this->select_where_array_table($this->tbl,['id'=>intval($id)]);
        $this->user_account_id=intval($user_account_id);
        return array_values($this->build_news_data($news))['0'];
    }
    public function get_news_by_user_account_id(int $user_account_id) {
        $this->db->select('n.*');
        $this->db->distinct();
        $this->db->from('news n');
        $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = \'news\' AND uar.user_account_id = ' . intval($user_account_id), 'inner');
        $news = $this->db->get()->result_array();
        $this->user_account_id=intval($user_account_id);
        return $this->build_news_data($news);
    }
    public function get_news_by_ids(array $news_ids, int $user_account_id): array {
        if (empty($news_ids)) return [];
        $this->db->where_in('id', $news_ids);
        $news_rows = $this->db->get('news')->result_array();
        $this->user_account_id = intval($user_account_id);
        return $this->build_news_data($news_rows);
    }
    public function get_all_runtime_reports_by_user(int $user_account_id): array {
        if ($user_account_id <= 0) return [];
        $this->db->select('rl.*');
        $this->db->distinct();
        $this->db->from('report_list rl');
        $this->db->join('user_account_relations uar', 
            '(' .
                'uar.user_account_id = ' . intval($user_account_id) . ' AND (' .
                    "(uar.target_table = 'report_list' AND uar.target_id = rl.id) OR " .
                    "(uar.target_table = 'news' AND uar.target_id = rl.news_id)" .
                ')' .
            ')',
            'inner'
        );
        $this->db->where('rl.run_time IS NOT NULL', null, false);
        $reports = $this->db->get()->result_array();
        $news_ids = array_unique(array_column($reports, 'news_id'));
        if (empty($news_ids)) return [];
        $this->just_run_time_report=true;
        $result=$this->get_news_by_ids($news_ids, $user_account_id);
        $this->just_run_time_report = false;
        return $result;
    }
    public function get_user_reports_with_news(int $user_account_id, int $limit = 10, int $offset = 0): array {
        if ($user_account_id <= 0) return ['data' => [], 'has_more' => false];
        $this->db->select('rl.*');
        $this->db->distinct();
        $this->db->from('report_list rl');
        $this->db->join('user_account_relations uar',
            'uar.target_table = \'report_list\' AND uar.target_id = rl.id AND uar.user_account_id = ' . intval($user_account_id),
            'inner'
        );
        $this->db->order_by('rl.id', 'DESC');
        $this->db->limit($limit + 1, $offset);
        $all_reports = $this->db->get()->result_array();
        if (empty($all_reports)) return ['data' => [], 'has_more' => false];
        $has_more = count($all_reports) > $limit;
        $all_reports = array_slice($all_reports, 0, $limit);
        $news_ids = array_unique(array_column($all_reports, 'news_id'));
        $this->just_news = true;
        $news_data_map = $this->get_news_by_ids($news_ids, $user_account_id);
        $this->just_news = false;
        $news_data_map = array_column($news_data_map, null, 'id');
        $report_data_map = $this->build_report_data($all_reports);
        $result = [];
        foreach ($all_reports as $report) {
            $result[] = [
                'report' => $report_data_map[$report['news_id']][0] ?? [],
                'news'   => $news_data_map[$report['news_id']] ?? null,
            ];
        }
        return [
            'data' => $result,
            'has_more' => $has_more
        ];
    }
    public function get_report_with_news_by_report_id(int $report_id, int $user_account_id): array {
        if ($report_id <= 0) return [];
        $report = $this->select_where_array_table($this->report, ['id' => $report_id]);
        if (empty($report)) return [];
        $report = $report[0];
        $news_id = $report['news_id'];
        $report_data = $this->build_report_data([$report]);
        $this->just_news = true;
        $news_data = $this->get_news_by_ids([$news_id], $user_account_id);
        $this->just_news = false;
        return [
            'report' => $report_data[$news_id][0] ?? [],
            'news' => $news_data[0] ?? null
        ];
    }
    // extera
    public function select_news(){
        return $this->db->get($this->tbl)->result_array();
    }
    public function select_news_where_user_account_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->tbl,['user_account_id'=>intval($id)]):false);
	}
    public function select_news_where_public_status_checking(){
	    return $this->select_where_array_table($this->tbl,['status'=>'checking','privacy'=>'public']);
	}
    public function select_news_where_status_seen(){
	    return $this->select_where_array_table($this->tbl,['status'=>'seen']);
	}
    public function add_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->tbl,$arr):false);
    }
    public function seen_weher_id($id){
        return (!empty($id) && intval($id)>0 && $this->edit_table($this->tbl,['status'=>'seen'],['id'=>intval($id)]));
    }
    public function change_description_where_id($id,$description){
        return (!empty($id) && intval($id)>0 && $this->edit_table($this->tbl,['description'=>$description??''],['id'=>intval($id)]));
    }
    public function seen_weher_id_and_user_account_id($id){
        return (!empty($id) && intval($id)>0 && $this->edit_table($this->tbl,['status'=>'seen'],['id'=>intval($id)]));
    }
    public function enable_disable_news($id,$en){
        return (!empty($id) && intval($id)>0 && $this->edit_table($this->tbl,['show_status'=>$en?'do':'dont'],['id'=>intval($id)]));
    }
    public function checking_weher_id_and_user_account_id($id){
        return (!empty($id) && intval($id)>0 && $this->edit_table($this->tbl,['status'=>'checking'],['id'=>intval($id)]));
    }
    public function select_report_where_news_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->report,['news_id'=>intval($id)]):false);
	}
    public function select_report_where_user_account_id($id){
	    return (!empty($id) && intval($id)?$this->select_where_array_table($this->report,['user_account_id'=>intval($id)]):false);
	}
    public function get_reports_by_account_or_news_ids($user_account_id, $news_array){
        if (empty($news_array)) {
            $news_array = [0];
        }
        $this->db->from($this->report);
        $this->db->group_start();
        $this->db->where('user_account_id', $user_account_id);
        $this->db->or_where_in('news_id', $news_array);
        $this->db->group_end();
        return $this->db->get()->result_array();
    }
}

    // public function get_news_by_user_account_ids(array $user_account_ids){
    //     if (empty($user_account_ids)) return [];
    //     $this->db->select('target_id');
    //     $this->db->from('user_account_relations');
    //     $this->db->where_in('user_account_id', $user_account_ids);
    //     $this->db->where('target_table', 'news');
    //     $news_ids_relation = $this->db->get()->result_array();
    //     if (empty($news_ids_relation)) {
    //         return [];
    //     }
    //     $news_ids = array_unique(array_column($news_ids_relation, 'target_id'));
    //     $this->db->select('
    //         n.*,
    //         a.city AS city,
    //         a.address AS address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon,
    //         uar.user_account_id,
    //         um.phone AS user_phone,
    //         u.name AS user_name,
    //         u.family AS user_family,
    //         user_img.url AS user_image_url
    //     ');
    //     $this->db->from('news n');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->where_in('n.id', $news_ids);
    //     $news_list = $this->db->get()->result_array();
    //     if (empty($news_list)) return [];
    //     $this->db->select('cr.target_id AS news_id, c.id, c.title');
    //     $this->db->from('category_relation cr');
    //     $this->db->join('category c', 'c.id = cr.category_id');
    //     $this->db->where('cr.target_table', 'news');
    //     $this->db->where_in('cr.target_id', $news_ids);
    //     $categories = $this->db->get()->result_array();
    //     $categoryMap = [];
    //     foreach ($categories as $cat) {
    //         $categoryMap[$cat['news_id']][] = [
    //             'id' => $cat['id'],
    //             'title' => $cat['title']
    //         ];
    //     }
    //     $this->db->select('mr.target_id AS news_id, m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id');
    //     $this->db->where('mr.target_table', 'news');
    //     $this->db->where_in('mr.target_id', $news_ids);
    //     $medias = $this->db->get()->result_array();
    //     $mediaMap = [];
    //     foreach ($medias as $media) {
    //         $mediaMap[$media['news_id']][] = [
    //             'id' => $media['id'],
    //             'url' => $media['url'],
    //             'type' => $media['type']
    //         ];
    //     }
    //     foreach ($news_list as &$news) {
    //         $id = $news['id'];
    //         $news['category'] = $categoryMap[$id] ?? [];
    //         $news['media'] = $mediaMap[$id] ?? [];
    //     }
    //     return $news_list;
    // }
    // public function select_news_where_category_id_status_checking_private($id){
    //     if (!empty($id) && is_numeric($id)) {
    //         $news=$this->select_where_array_table($this->category_relation,['category_id'=>$id]);
    //         $news_array_id=[];
    //         if(!empty($news))
    //             foreach($news as $a){
    //                 if(!empty($a) && !empty($a['news_id']) && intval($a['news_id'])>0)
    //                     $news_array_id[]=intval($a['news_id']);
    //             }
    //         if(!empty($news_array_id)){
    //             $this->db->from($this->tbl);
    //             $this->db->group_start();
    //             $this->db->where('status', 'checking');
    //             $this->db->where('privacy', 'private');
    //             $this->db->where_in('id', $news_array_id);
    //             $this->db->group_end();
    //             return $this->db->get()->result_array();
    //         }
    //     }
    //     return [];
	// }