<?php

class Report_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
	}
    private $report="report_list";
    private function add_to_table_return_id($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert($tbl,$arr)?$this->db->insert_id():false);
    }
    private function edit_table($tbl,$arr,$where){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && !empty($where) && is_array($where) && $this->db->update($tbl,$arr,$where));
    }
    public function add_report_return_id($arr){
        return (!empty($arr) && is_array($arr)?$this->add_to_table_return_id($this->report,$arr):false);
    }
    public function edit_report_weher_id($arr,$id){
        return (!empty($id) && intval($id)>0 && !empty($arr) && is_array($arr) && $this->edit_table($this->report,$arr,['id'=>intval($id)]));
    }
}

    // public function get_reports_by_id($id){
    //     $this->db->select('
    //         rl.*,
    //         rl.news_id AS news_id,
    //         rl.description AS report_description,
    //         n.privacy AS news_privacy,
    //         n.status AS news_status,
    //         n.description AS news_description,
    //         a.city,
    //         a.address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon,
    //         reporter_uar.user_account_id AS reporter_user_account_id,
    //         news_uar.user_account_id AS news_user_account_id,
    //         um.phone AS reporter_phone,
    //         u.name AS reporter_name,
    //         u.family AS reporter_family,
    //         user_img.url AS reporter_user_image_url,
    //         um2.phone AS news_user_phone,
    //         u2.name AS news_user_name,
    //         u2.family AS news_user_family,
    //         user_img2.url AS news_user_image_url
    //     ');
    //     $this->db->from('report_list rl');
    //     $this->db->join('news n', 'n.id = rl.news_id', 'left');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations reporter_uar', 'reporter_uar.target_id = rl.id AND reporter_uar.target_table = "report_list"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = reporter_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->join('user_account_relations news_uar', 'news_uar.target_id = n.id AND news_uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua2', 'ua2.id = news_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um2', 'um2.id = ua2.user_mobile_id', 'left');
    //     $this->db->join('users u2', 'u2.id = um2.user_id', 'left');
    //     $this->db->join('media user_img2', 'user_img2.id = um2.image_id', 'left');
    //     $this->db->where('rl.id', $id);
    //     $report = $this->db->get()->row_array();

    //     if (!$report || !isset($report['news_id'])) {
    //         return null;
    //     }

    //     // دسته‌بندی‌های خبر
    //     $this->db->select('c.id, c.title');
    //     $this->db->from('category_relation cr');
    //     $this->db->join('category c', 'c.id = cr.category_id', 'left');
    //     $this->db->where('cr.target_id', $report['news_id']);
    //     $this->db->where('cr.target_table', 'news');
    //     $report['category'] = $this->db->get()->result_array();

    //     // مدیای خبر
    //     $this->db->select('m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id', 'left');
    //     $this->db->where('mr.target_id', $report['news_id']);
    //     $this->db->where('mr.target_table', 'news');
    //     $report['news_media'] = $this->db->get()->result_array();

    //     // مدیای گزارش
    //     $this->db->select('m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id', 'left');
    //     $this->db->where('mr.target_id', $report['id']);
    //     $this->db->where('mr.target_table', 'report_list');
    //     $report['report_media'] = $this->db->get()->result_array();

    //     return $report;
    // }
    // public function add_report($arr){
    //     return (!empty($arr) && is_array($arr) && $this->add_to_table($this->report,$arr));
    // }
    // public function get_reports_by_news_id($id){
    //     $this->db->select('
    //         rl.*,
    //         rl.description AS report_description,
    //         a.city AS city,
    //         a.address AS address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon,
    //         reporter_uar.user_account_id AS reporter_user_account_id,
    //         um.phone AS reporter_phone,
    //         u.name AS reporter_name,
    //         u.family AS reporter_family,
    //         user_img.url AS reporter_user_image_url
    //     ');
    //     $this->db->from('report_list rl');
    //     $this->db->join('news n', 'n.id = rl.news_id', 'left');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations reporter_uar', 'reporter_uar.target_id = rl.id AND reporter_uar.target_table = "report_list"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = reporter_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->where('rl.news_id', $id);
    //     $reports = $this->db->get()->result_array();
    //     $this->db->select('
    //         n.*,
    //         ua2.id AS news_user_account_id,
    //         um2.phone AS news_user_phone,
    //         u2.name AS news_user_name,
    //         u2.family AS news_user_family,
    //         user_img2.url AS news_user_image_url
    //     ');
    //     $this->db->from('news n');
    //     $this->db->join('user_account_relations news_uar', 'news_uar.target_id = n.id AND news_uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua2', 'ua2.id = news_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um2', 'um2.id = ua2.user_mobile_id', 'left');
    //     $this->db->join('users u2', 'u2.id = um2.user_id', 'left');
    //     $this->db->join('media user_img2', 'user_img2.id = um2.image_id', 'left');
    //     $this->db->where('n.id', $id);
    //     $news = $this->db->get()->row_array();
    //     $this->db->select('c.id, c.title');
    //     $this->db->from('category_relation cr');
    //     $this->db->join('category c', 'c.id = cr.category_id', 'left');
    //     $this->db->where('cr.target_id', $id);
    //     $this->db->where('cr.target_table', 'news');
    //     $news['category'] = $this->db->get()->result_array();
    //     $this->db->select('m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id', 'left');
    //     $this->db->where('mr.target_id', $id);
    //     $this->db->where('mr.target_table', 'news');
    //     $news['news_media'] = $this->db->get()->result_array();
    //     foreach ($reports as &$report) {
    //         $this->db->select('m.id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id', 'left');
    //         $this->db->where('mr.target_id', $report['id']);
    //         $this->db->where('mr.target_table', 'report_list');
    //         $report['report_media'] = $this->db->get()->result_array();
    //     }
    //     return ['report' => $reports, 'news' => $news];
    // }
    // public function get_reports_by_id($id){
    //     $this->db->select('
    //         rl.*,
    //         n.*,
    //         a.city AS city,
    //         a.address AS address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon,
    //         reporter_uar.user_account_id AS reporter_user_account_id,
    //         news_uar.user_account_id AS news_user_account_id,
    //         um.phone AS reporter_phone,
    //         u.name AS reporter_name,
    //         u.family AS reporter_family,
    //         user_img.url AS reporter_user_image_url,
    //         um2.phone AS news_user_phone,
    //         u2.name AS news_user_name,
    //         u2.family AS news_user_family,
    //         user_img2.url AS news_user_image_url
    //     ');
    //     $this->db->from('report_list rl');
    //     $this->db->join('news n', 'n.id = rl.news_id', 'left');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations reporter_uar', 'reporter_uar.target_id = rl.id AND reporter_uar.target_table = "report_list"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = reporter_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->join('user_account_relations news_uar', 'news_uar.target_id = n.id AND news_uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua2', 'ua2.id = news_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um2', 'um2.id = ua2.user_mobile_id', 'left');
    //     $this->db->join('users u2', 'u2.id = um2.user_id', 'left');
    //     $this->db->join('media user_img2', 'user_img2.id = um2.image_id', 'left');
    //     $this->db->where('rl.id', $id);
    //     $report = $this->db->get()->row_array();
    //     if (!$report) return null;
    //     $this->db->select('c.id, c.title');
    //     $this->db->from('category_relation cr');
    //     $this->db->join('category c', 'c.id = cr.category_id', 'left');
    //     $this->db->where('cr.target_id', $report['news_id']);
    //     $this->db->where('cr.target_table', 'news');
    //     $report['category'] = $this->db->get()->result_array();
    //     $this->db->select('m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id', 'left');
    //     $this->db->where('mr.target_id', $report['news_id']);
    //     $this->db->where('mr.target_table', 'news');
    //     $news_media = $this->db->get()->result_array();
    //     foreach ($news_media as &$media) {
    //         $media['news_id'] = $report['news_id'];
    //     }
    //     $report['news_media'] = $news_media;
    //     $this->db->select('m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id', 'left');
    //     $this->db->where('mr.target_id', $report['id']);
    //     $this->db->where('mr.target_table', 'report_list');
    //     $report_media = $this->db->get()->result_array();
    //     foreach ($report_media as &$media) {
    //         $media['report_id'] = $report['id'];
    //     }
    //     $report['report_media'] = $report_media;
    //     return $report;
    // }
    // public function get_reports_by_user_account_ids(array $user_account_ids, int $limit = null, int $offset = null) {
    //     if (empty($user_account_ids)) return ['data' => [], 'total' => 0];
    //     $this->db->from('report_list rl');
    //     $this->db->join('user_account_relations reporter_uar', 'reporter_uar.target_id = rl.id AND reporter_uar.target_table = "report_list"', 'left');
    //     $this->db->where_in('reporter_uar.user_account_id', $user_account_ids);
    //     $total = $this->db->count_all_results();
    //     $this->db->select('
    //         rl.*,
    //         n.*,
    //         a.city AS city,
    //         a.address AS address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon,
    //         reporter_uar.user_account_id AS reporter_user_account_id,
    //         ua2_uar.user_account_id AS news_user_account_id,
    //         um.phone AS reporter_phone,
    //         u.name AS reporter_name,
    //         u.family AS reporter_family,
    //         user_img.url AS reporter_user_image_url,
    //         um2.phone AS news_user_phone,
    //         u2.name AS news_user_name,
    //         u2.family AS news_user_family,
    //         user_img2.url AS news_user_image_url
    //     ');
    //     $this->db->from('report_list rl');
    //     $this->db->join('news n', 'n.id = rl.news_id', 'left');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations reporter_uar', 'reporter_uar.target_id = rl.id AND reporter_uar.target_table = "report_list"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = reporter_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->join('user_account_relations ua2_uar', 'ua2_uar.target_id = n.id AND ua2_uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua2', 'ua2.id = ua2_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um2', 'um2.id = ua2.user_mobile_id', 'left');
    //     $this->db->join('users u2', 'u2.id = um2.user_id', 'left');
    //     $this->db->join('media user_img2', 'user_img2.id = um2.image_id', 'left');
    //     $this->db->where_in('reporter_uar.user_account_id', $user_account_ids);
    //     if ($limit !== null) {
    //         if ($offset !== null) {
    //             $this->db->limit($limit, $offset);
    //         } else {
    //             $this->db->limit($limit);
    //         }
    //     }
    //     $reports = $this->db->get()->result_array();
    //     $news_ids = array_column($reports, 'news_id');
    //     $categories = [];
    //     if (!empty($news_ids)) {
    //         $this->db->select('cr.target_id AS news_id, c.id, c.title');
    //         $this->db->from('category_relation cr');
    //         $this->db->join('category c', 'c.id = cr.category_id');
    //         $this->db->where_in('cr.target_id', $news_ids);
    //         $this->db->where('cr.target_table', 'news');
    //         $categories = $this->db->get()->result_array();
    //     }
    //     $media_news = [];
    //     if (!empty($news_ids)) {
    //         $this->db->select('mr.target_id AS news_id, m.id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id');
    //         $this->db->where_in('mr.target_id', $news_ids);
    //         $this->db->where('mr.target_table', 'news');
    //         $media_news = $this->db->get()->result_array();
    //     }
    //     $report_ids = array_column($reports, 'id');
    //     $media_report = [];
    //     if (!empty($report_ids)) {
    //         $this->db->select('mr.target_id AS report_id, m.id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id');
    //         $this->db->where_in('mr.target_id', $report_ids);
    //         $this->db->where('mr.target_table', 'report_list');
    //         $media_report = $this->db->get()->result_array();
    //     }
    //     $grouped_categories = [];
    //     foreach ($categories as $cat) {
    //         $grouped_categories[$cat['news_id']][] = [
    //             'id' => $cat['id'],
    //             'title' => $cat['title']
    //         ];
    //     }
    //     $grouped_news_media = [];
    //     foreach ($media_news as $media) {
    //         $grouped_news_media[$media['news_id']][] = $media;
    //     }
    //     $grouped_report_media = [];
    //     foreach ($media_report as $media) {
    //         $grouped_report_media[$media['report_id']][] = $media;
    //     }
    //     foreach ($reports as &$report) {
    //         $news_id = $report['news_id'];
    //         $report_id = $report['id'];
    //         $report['category'] = $grouped_categories[$news_id] ?? [];
    //         $report['news_media'] = $grouped_news_media[$news_id] ?? [];
    //         $report['report_media'] = $grouped_report_media[$report_id] ?? [];
    //     }
    //     $grouped = [];
    //     foreach ($reports as $report) {
    //         $news_id = $report['news_id'];
    //         if (!isset($grouped[$news_id])) {
    //             $grouped[$news_id] = [
    //                 'news' => [
    //                     'id' => $news_id,
    //                     'title' => $report['title'] ?? null,
    //                     'description' => $report['description'] ?? null,
    //                     'user_account_id' => $report['news_user_account_id'],
    //                     'user_name' => $report['news_user_name'],
    //                     'user_family' => $report['news_user_family'],
    //                     'user_phone' => $report['news_user_phone'],
    //                     'user_image_url' => $report['news_user_image_url'],
    //                     'address' => [
    //                         'city' => $report['city'],
    //                         'address' => $report['address'],
    //                         'lat' => $report['address_lat'],
    //                         'lon' => $report['address_lon'],
    //                     ],
    //                     'category' => $report['category'] ?? [],
    //                     'news_media' => $report['news_media'] ?? [],
    //                 ],
    //                 'report_list' => [],
    //             ];
    //         }
    //         $grouped[$news_id]['report_list'][] = [
    //             'id' => $report['id'],
    //             'token' => $report['token'],
    //             'description' => $report['report_description'] ?? $report['description'],
    //             'user_account_id' => $report['reporter_user_account_id'],
    //             'user_name' => $report['reporter_name'],
    //             'user_family' => $report['reporter_family'],
    //             'user_phone' => $report['reporter_phone'],
    //             'user_image_url' => $report['reporter_user_image_url'],
    //             'report_media' => $report['report_media'] ?? [],
    //         ];
    //     }
    //     return ['data' => array_values($grouped), 'total' => $total];
    // }
    // public function get_reports_by_user_account_id($id){
    //     $this->db->select('
    //         rl.*,
    //         n.*,
    //         a.city AS city,
    //         a.address AS address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon,
    //         reporter_uar.user_account_id AS reporter_user_account_id,
    //         ua2_uar.user_account_id AS news_user_account_id,
    //         um.phone AS reporter_phone,
    //         u.name AS reporter_name,
    //         u.family AS reporter_family,
    //         user_img.url AS reporter_user_image_url,
    //         um2.phone AS news_user_phone,
    //         u2.name AS news_user_name,
    //         u2.family AS news_user_family,
    //         user_img2.url AS news_user_image_url
    //     ');
    //     $this->db->from('report_list rl');
    //     $this->db->join('news n', 'n.id = rl.news_id', 'left');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations reporter_uar', 'reporter_uar.target_id = rl.id AND reporter_uar.target_table = "report_list"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = reporter_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->join('user_account_relations ua2_uar', 'ua2_uar.target_id = n.id AND ua2_uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua2', 'ua2.id = ua2_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um2', 'um2.id = ua2.user_mobile_id', 'left');
    //     $this->db->join('users u2', 'u2.id = um2.user_id', 'left');
    //     $this->db->join('media user_img2', 'user_img2.id = um2.image_id', 'left');
    //     $this->db->where('reporter_uar.user_account_id', $id);
    //     $reports = $this->db->get()->result_array();
    //     $news_ids = array_column($reports, 'news_id');
    //     $categories = [];
    //     if (!empty($news_ids)) {
    //         $this->db->select('cr.target_id AS news_id, c.id, c.title');
    //         $this->db->from('category_relation cr');
    //         $this->db->join('category c', 'c.id = cr.category_id');
    //         $this->db->where_in('cr.target_id', $news_ids);
    //         $this->db->where('cr.target_table', 'news');
    //         $categories = $this->db->get()->result_array();
    //     }
    //     $media_news = [];
    //     if (!empty($news_ids)) {
    //         $this->db->select('mr.target_id AS news_id, m.id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id');
    //         $this->db->where_in('mr.target_id', $news_ids);
    //         $this->db->where('mr.target_table', 'news');
    //         $media_news = $this->db->get()->result_array();
    //     }
    //     $report_ids = array_column($reports, 'id');
    //     $media_report = [];
    //     if (!empty($report_ids)) {
    //         $this->db->select('mr.target_id AS report_id, m.id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id');
    //         $this->db->where_in('mr.target_id', $report_ids);
    //         $this->db->where('mr.target_table', 'report_list');
    //         $media_report = $this->db->get()->result_array();
    //     }
    //     $grouped_categories = [];
    //     foreach ($categories as $cat) {
    //         $grouped_categories[$cat['news_id']][] = [
    //             'id' => $cat['id'],
    //             'title' => $cat['title']
    //         ];
    //     }
    //     $grouped_news_media = [];
    //     foreach ($media_news as $media) {
    //         $grouped_news_media[$media['news_id']][] = $media;
    //     }
    //     $grouped_report_media = [];
    //     foreach ($media_report as $media) {
    //         $grouped_report_media[$media['report_id']][] = $media;
    //     }
    //     foreach ($reports as &$report) {
    //         $news_id = $report['news_id'];
    //         $report_id = $report['id'];
    //         $report['category'] = $grouped_categories[$news_id] ?? [];
    //         $report['news_media'] = $grouped_news_media[$news_id] ?? [];
    //         $report['report_media'] = $grouped_report_media[$report_id] ?? [];
    //     }
    //     $grouped = [];
    //     foreach ($reports as $report) {
    //         $news_id = $report['news_id'];
    //         if (!isset($grouped[$news_id])) {
    //             $grouped[$news_id] = [
    //                 'news' => [
    //                     'id' => $news_id,
    //                     'title' => $report['title'] ?? null,
    //                     'description' => $report['description'] ?? null,
    //                     'user_account_id' => $report['news_user_account_id'],
    //                     'user_name' => $report['news_user_name'],
    //                     'user_family' => $report['news_user_family'],
    //                     'user_phone' => $report['news_user_phone'],
    //                     'user_image_url' => $report['news_user_image_url'],
    //                     'address' => [
    //                         'city' => $report['city'],
    //                         'address' => $report['address'],
    //                         'lat' => $report['address_lat'],
    //                         'lon' => $report['address_lon'],
    //                     ],
    //                     'category' => $report['category'] ?? [],
    //                     'news_media' => $report['news_media'] ?? [],
    //                 ],
    //                 'report_list' => [],
    //             ];
    //         }
    //         $grouped[$news_id]['report_list'][] = [
    //             'id' => $report['id'],
    //             'description' => $report['report_description'] ?? $report['description'],
    //             'user_account_id' => $report['reporter_user_account_id'],
    //             'user_name' => $report['reporter_name'],
    //             'user_family' => $report['reporter_family'],
    //             'user_phone' => $report['reporter_phone'],
    //             'user_image_url' => $report['reporter_user_image_url'],
    //             'report_media' => $report['report_media'] ?? [],
    //         ];
    //     }
    //     return array_values($grouped);
    // }
    // public function get_reports_by_user_account_id($user_account_id) {
    //     // 1. گرفتن گزارش‌ها به همراه آیدی خبر
    //     $this->db->select('rl.*, rl.id as report_id, rl.description as report_description, rl.news_id');
    //     $this->db->from('report_list rl');
    //     $this->db->join('user_account_relations uar', '
    //         uar.target_id = rl.id AND 
    //         uar.target_table = "report_list" AND 
    //         uar.user_account_id = ' . intval($user_account_id),
    //         'inner'
    //     );
    //     $reports = $this->db->get()->result_array();

    //     if (empty($reports)) {
    //         return [];
    //     }

    //     // استخراج آیدی‌ها برای query های بعدی
    //     $report_ids = array_column($reports, 'report_id');
    //     $news_ids = array_unique(array_column($reports, 'news_id'));

    //     // 2. گرفتن اطلاعات خبرهای مرتبط
    //     $this->db->select('n.*, n.id as news_id');
    //     $this->db->from('news n');
    //     $this->db->where_in('n.id', $news_ids);
    //     $news_list = $this->db->get()->result_array();
    //     $newsMap = array_column($news_list, null, 'news_id');

    //     // 3. گرفتن اطلاعات کاربران سازنده خبر
    //     $this->db->select('
    //         uar.target_id as news_id,
    //         ua.id as user_account_id,
    //         um.phone,
    //         u.name,
    //         u.family,
    //         m.url as user_image_url
    //     ');
    //     $this->db->from('user_account_relations uar');
    //     $this->db->join('user_account ua', 'ua.id = uar.user_account_id');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id');
    //     $this->db->join('users u', 'u.id = um.user_id');
    //     $this->db->join('media m', 'm.id = um.image_id', 'left');
    //     $this->db->where('uar.target_table', 'news');
    //     $this->db->where_in('uar.target_id', $news_ids);
    //     $news_owners = $this->db->get()->result_array();
    //     $newsOwnerMap = [];
    //     foreach ($news_owners as $row) {
    //         $newsOwnerMap[$row['news_id']] = $row;
    //     }

    //     // 4. گرفتن اطلاعات دسته‌بندی‌ها
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

    //     // 5. گرفتن اطلاعات آدرس خبر
    //     $this->db->select('ar.target_id AS news_id, a.city, a.address, a.lat, a.lon');
    //     $this->db->from('address_relation ar');
    //     $this->db->join('addresses a', 'a.id = ar.address_id');
    //     $this->db->where('ar.target_table', 'news');
    //     $this->db->where_in('ar.target_id', $news_ids);
    //     $addresses = $this->db->get()->result_array();
    //     $addressMap = array_column($addresses, null, 'news_id');

    //     // 6. گرفتن مدیای خبر
    //     $this->db->select('mr.target_id AS news_id, m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id');
    //     $this->db->where('mr.target_table', 'news');
    //     $this->db->where_in('mr.target_id', $news_ids);
    //     $media_news = $this->db->get()->result_array();
    //     $mediaNewsMap = [];
    //     foreach ($media_news as $row) {
    //         $mediaNewsMap[$row['news_id']][] = $row;
    //     }

    //     // 7. گرفتن مدیای گزارش
    //     $this->db->select('mr.target_id AS report_id, m.id, m.url, m.type');
    //     $this->db->from('media_relation mr');
    //     $this->db->join('media m', 'm.id = mr.media_id');
    //     $this->db->where('mr.target_table', 'report_list');
    //     $this->db->where_in('mr.target_id', $report_ids);
    //     $media_report = $this->db->get()->result_array();
    //     $mediaReportMap = [];
    //     foreach ($media_report as $row) {
    //         $mediaReportMap[$row['report_id']][] = $row;
    //     }

    //     // 8. گرفتن اطلاعات کاربر گزارش‌دهنده
    //     $this->db->select('
    //         uar.target_id as report_id,
    //         ua.id as user_account_id,
    //         um.phone,
    //         u.name,
    //         u.family,
    //         m.url as user_image_url
    //     ');
    //     $this->db->from('user_account_relations uar');
    //     $this->db->join('user_account ua', 'ua.id = uar.user_account_id');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id');
    //     $this->db->join('users u', 'u.id = um.user_id');
    //     $this->db->join('media m', 'm.id = um.image_id', 'left');
    //     $this->db->where('uar.target_table', 'report_list');
    //     $this->db->where_in('uar.target_id', $report_ids);
    //     $reporters = $this->db->get()->result_array();
    //     $reporterMap = [];
    //     foreach ($reporters as $row) {
    //         $reporterMap[$row['report_id']] = $row;
    //     }

    //     // 9. نهایی‌سازی خروجی
    //     $grouped = [];

    //     foreach ($reports as $report) {
    //         $news_id = $report['news_id'];
    //         $report_id = $report['report_id'];

    //         if (!isset($grouped[$news_id])) {
    //             $grouped[$news_id] = [
    //                 'news' => [
    //                     'id' => $news_id,
    //                     'title' => $newsMap[$news_id]['title'] ?? null,
    //                     'description' => $newsMap[$news_id]['description'] ?? null,
    //                     'user_account_id' => $newsOwnerMap[$news_id]['user_account_id'] ?? null,
    //                     'user_name' => $newsOwnerMap[$news_id]['name'] ?? null,
    //                     'user_family' => $newsOwnerMap[$news_id]['family'] ?? null,
    //                     'user_phone' => $newsOwnerMap[$news_id]['phone'] ?? null,
    //                     'user_image_url' => $newsOwnerMap[$news_id]['user_image_url'] ?? null,
    //                     'address' => $addressMap[$news_id] ?? [],
    //                     'category' => $categoryMap[$news_id] ?? [],
    //                     'news_media' => $mediaNewsMap[$news_id] ?? [],
    //                 ],
    //                 'report_list' => [],
    //             ];
    //         }

    //         $grouped[$news_id]['report_list'][] = [
    //             'id' => $report_id,
    //             'description' => $report['report_description'],
    //             'user_account_id' => $reporterMap[$report_id]['user_account_id'] ?? null,
    //             'user_name' => $reporterMap[$report_id]['name'] ?? null,
    //             'user_family' => $reporterMap[$report_id]['family'] ?? null,
    //             'user_phone' => $reporterMap[$report_id]['phone'] ?? null,
    //             'user_image_url' => $reporterMap[$report_id]['user_image_url'] ?? null,
    //             'report_media' => $mediaReportMap[$report_id] ?? [],
    //         ];
    //     }

    //     return array_values($grouped);
    // }
    // public function get_reports_by_user_account_id($user_account_id) {
    //     // 1. گرفتن گزارش‌ها به همراه آیدی خبر
    //     $this->db->select('rl.*, rl.id as report_id, rl.description as report_description, rl.news_id');
    //     $this->db->from('report_list rl');
    //     $this->db->join('user_account_relations uar', '
    //         uar.target_id = rl.id AND 
    //         uar.target_table = "report_list" AND 
    //         uar.user_account_id = ' . intval($user_account_id),
    //         'inner'
    //     );
    //     $reports = $this->db->get()->result_array();
    //     // return $reports;
    //     if (empty($reports)) {
    //         return [];
    //     }

    //     // استخراج آیدی‌ها برای query های بعدی
    //     $report_ids = array_column($reports, 'report_id');
    //     $news_ids = array_unique(array_column($reports, 'news_id'));

    //     // 2. گرفتن اطلاعات خبرهای مرتبط
    //     $news_list = $this->safe_where_in_query(
    //         'n.*, n.id as news_id',
    //         'news n',
    //         'n.id',
    //         $news_ids
    //     );
    //     $newsMap = array_column($news_list, null, 'news_id');

    //     // 3. گرفتن اطلاعات کاربران سازنده خبر
    //     $news_owners = $this->safe_where_in_query(
    //         'uar.target_id as news_id, ua.id as user_account_id, um.phone, u.name, u.family, m.url as user_image_url',
    //         'user_account_relations uar',
    //         'uar.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('user_account ua', 'ua.id = uar.user_account_id');
    //             $db->join('user_mobile um', 'um.id = ua.user_mobile_id');
    //             $db->join('users u', 'u.id = um.user_id');
    //             $db->join('media m', 'm.id = um.image_id', 'left');
    //             $db->where('uar.target_table', 'news');
    //         }
    //     );
    //     $newsOwnerMap = [];
    //     foreach ($news_owners as $row) {
    //         $newsOwnerMap[$row['news_id']] = $row;
    //     }

    //     // 4. گرفتن اطلاعات دسته‌بندی‌ها
    //     $categories = $this->safe_where_in_query(
    //         'cr.target_id AS news_id, c.id, c.title',
    //         'category_relation cr',
    //         'cr.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('category c', 'c.id = cr.category_id');
    //             $db->where('cr.target_table', 'news');
    //         }
    //     );
    //     $categoryMap = [];
    //     foreach ($categories as $cat) {
    //         $categoryMap[$cat['news_id']][] = [
    //             'id' => $cat['id'],
    //             'title' => $cat['title']
    //         ];
    //     }

    //     // 5. گرفتن اطلاعات آدرس خبر
    //     $addresses = $this->safe_where_in_query(
    //         'ar.target_id AS news_id, a.city, a.address, a.lat, a.lon',
    //         'address_relation ar',
    //         'ar.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('addresses a', 'a.id = ar.address_id');
    //             $db->where('ar.target_table', 'news');
    //         }
    //     );
    //     $addressMap = array_column($addresses, null, 'news_id');

    //     // 6. گرفتن مدیای خبر
    //     $media_news = $this->safe_where_in_query(
    //         'mr.target_id AS news_id, m.id, m.url, m.type',
    //         'media_relation mr',
    //         'mr.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('media m', 'm.id = mr.media_id');
    //             $db->where('mr.target_table', 'news');
    //         }
    //     );
    //     $mediaNewsMap = [];
    //     foreach ($media_news as $row) {
    //         $mediaNewsMap[$row['news_id']][] = $row;
    //     }

    //     // 7. گرفتن مدیای گزارش
    //     $media_report = $this->safe_where_in_query(
    //         'mr.target_id AS report_id, m.id, m.url, m.type',
    //         'media_relation mr',
    //         'mr.target_id',
    //         $report_ids,
    //         function($db) {
    //             $db->join('media m', 'm.id = mr.media_id');
    //             $db->where('mr.target_table', 'report_list');
    //         }
    //     );
    //     $mediaReportMap = [];
    //     foreach ($media_report as $row) {
    //         $mediaReportMap[$row['report_id']][] = $row;
    //     }

    //     // 8. گرفتن اطلاعات کاربر گزارش‌دهنده
    //     $reporters = $this->safe_where_in_query(
    //         'uar.target_id as report_id, ua.id as user_account_id, um.phone, u.name, u.family, m.url as user_image_url',
    //         'user_account_relations uar',
    //         'uar.target_id',
    //         $report_ids,
    //         function($db) {
    //             $db->join('user_account ua', 'ua.id = uar.user_account_id');
    //             $db->join('user_mobile um', 'um.id = ua.user_mobile_id');
    //             $db->join('users u', 'u.id = um.user_id');
    //             $db->join('media m', 'm.id = um.image_id', 'left');
    //             $db->where('uar.target_table', 'report_list');
    //         }
    //     );
    //     $reporterMap = [];
    //     foreach ($reporters as $row) {
    //         $reporterMap[$row['report_id']] = $row;
    //     }

    //     // 9. نهایی‌سازی خروجی
    //     $grouped = [];

    //     foreach ($reports as $report) {
    //         $news_id = $report['news_id'];
    //         $report_id = $report['report_id'];

    //         if (!isset($grouped[$news_id])) {
    //             $grouped[$news_id] = [
    //                 // 'id'=>$news_id,
    //                 'news' => [
    //                     'id' => $news_id,
    //                     'title' => $newsMap[$news_id]['title'] ?? null,
    //                     'description' => $newsMap[$news_id]['description'] ?? null,
    //                     'status' => $newsMap[$news_id]['status'] ?? null,
    //                     'user_account_id' => $newsOwnerMap[$news_id]['user_account_id'] ?? null,
    //                     'user_name' => $newsOwnerMap[$news_id]['name'] ?? null,
    //                     'user_family' => $newsOwnerMap[$news_id]['family'] ?? null,
    //                     'user_phone' => $newsOwnerMap[$news_id]['phone'] ?? null,
    //                     'user_image_url' => $newsOwnerMap[$news_id]['user_image_url'] ?? null,
    //                     'address' => $addressMap[$news_id] ?? [],
    //                     'category' => $categoryMap[$news_id] ?? [],
    //                     'news_media' => $mediaNewsMap[$news_id] ?? [],
    //                 ],
    //                 'report_list' => [],
    //             ];
    //         }

    //         $grouped[$news_id]['report_list'][] = [
    //             'id' => $report_id,
    //             'description' => $report['report_description'],
    //             'user_account_id' => $reporterMap[$report_id]['user_account_id'] ?? null,
    //             'user_name' => $reporterMap[$report_id]['name'] ?? null,
    //             'user_family' => $reporterMap[$report_id]['family'] ?? null,
    //             'user_phone' => $reporterMap[$report_id]['phone'] ?? null,
    //             'user_image_url' => $reporterMap[$report_id]['user_image_url'] ?? null,
    //             'report_media' => $mediaReportMap[$report_id] ?? [],
    //         ];
    //     }

    //     return array_values($grouped);
    // }
    // public function get_reports_by_news_user_account_id($id){
    //     $this->db->select('
    //         rl.*,
    //         n.*,
    //         a.city AS city,
    //         a.address AS address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon,
    //         reporter_uar.user_account_id AS reporter_user_account_id,
    //         um.phone AS reporter_phone,
    //         u.name AS reporter_name,
    //         u.family AS reporter_family,
    //         user_img.url AS reporter_user_image_url,
    //         news_uar.user_account_id AS news_user_account_id,
    //         um2.phone AS news_user_phone,
    //         u2.name AS news_user_name,
    //         u2.family AS news_user_family,
    //         user_img2.url AS news_user_image_url
    //     ');
    //     $this->db->from('report_list rl');
    //     $this->db->join('news n', 'n.id = rl.news_id', 'left');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations reporter_uar', 'reporter_uar.target_id = rl.id AND reporter_uar.target_table = "report_list"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = reporter_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->join('user_account_relations news_uar', 'news_uar.target_id = n.id AND news_uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua2', 'ua2.id = news_uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um2', 'um2.id = ua2.user_mobile_id', 'left');
    //     $this->db->join('users u2', 'u2.id = um2.user_id', 'left');
    //     $this->db->join('media user_img2', 'user_img2.id = um2.image_id', 'left');
    //     $this->db->where('news_uar.user_account_id', $id);
    //     $reports = $this->db->get()->result_array();
    //     $report_ids = array_column($reports, 'id');
    //     $report_media = [];
    //     if (!empty($report_ids)) {
    //         $this->db->select('mr.target_id AS report_id, m.id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id');
    //         $this->db->where_in('mr.target_id', $report_ids);
    //         $this->db->where('mr.target_table', 'report_list');
    //         $report_media = $this->db->get()->result_array();
    //     }
    //     $news_ids = array_column($reports, 'news_id');
    //     $news_media = [];
    //     if (!empty($news_ids)) {
    //         $this->db->select('mr.target_id AS news_id, m.id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id');
    //         $this->db->where_in('mr.target_id', $news_ids);
    //         $this->db->where('mr.target_table', 'news');
    //         $news_media = $this->db->get()->result_array();
    //     }
    //     $categories = [];
    //     if (!empty($news_ids)) {
    //         $this->db->select('cr.target_id AS news_id, c.id, c.title');
    //         $this->db->from('category_relation cr');
    //         $this->db->join('category c', 'c.id = cr.category_id');
    //         $this->db->where_in('cr.target_id', $news_ids);
    //         $this->db->where('cr.target_table', 'news');
    //         $categories = $this->db->get()->result_array();
    //     }
    //     $grouped_news_media = [];
    //     foreach ($news_media as $media) {
    //         $grouped_news_media[$media['news_id']][] = $media;
    //     }
    //     $grouped_report_media = [];
    //     foreach ($report_media as $media) {
    //         $grouped_report_media[$media['report_id']][] = $media;
    //     }
    //     $grouped_categories = [];
    //     foreach ($categories as $cat) {
    //         $grouped_categories[$cat['news_id']][] = [
    //             'id' => $cat['id'],
    //             'title' => $cat['title']
    //         ];
    //     }
    //     foreach ($reports as &$report) {
    //         $report_id = $report['id'];
    //         $news_id = $report['news_id'];
    //         $report['report_media'] = $grouped_report_media[$report_id] ?? [];
    //         $report['news_media'] = $grouped_news_media[$news_id] ?? [];
    //         $report['category'] = $grouped_categories[$news_id] ?? [];
    //     }
    //     return $reports;
    // }
    // private function safe_where_in_query($select, $from, $column, $values, $callback = null, $chunkSize = 500) {
    //     $result = [];
    //     foreach (array_chunk($values, $chunkSize) as $chunk) {
    //         $this->db->select($select);
    //         $this->db->from($from);
    //         $this->db->where_in($column, $chunk);
    //         if ($callback && is_callable($callback)) {
    //             $callback($this->db);
    //         }
    //         $result = array_merge($result, $this->db->get()->result_array());
    //     }
    //     return $result;
    // }
    // public function get_reports_by_news_user_account_id($user_account_id) {
    //     // مرحله ۱: گرفتن آیدی خبرهایی که متعلق به این کاربر هستن
    //     $news_ids = array_column(
    //         $this->db->select('target_id')
    //             ->from('user_account_relations')
    //             ->where('target_table', 'news')
    //             ->where('user_account_id', $user_account_id)
    //             ->get()->result_array(),
    //         'target_id'
    //     );

    //     if (empty($news_ids)) return [];
    //     // مرحله جدید: گرفتن اطلاعات خود خبر
    //     $news_rows = $this->safe_where_in_query(
    //         '*',
    //         'news ',
    //         'id',
    //         $news_ids
    //     );
    //     $newsMap = array_column($news_rows, null, 'id');

    //     // مرحله ۲: گرفتن گزارش‌ها
    //     $reports = $this->safe_where_in_query(
    //         'rl.*, rl.id as report_id, rl.description as report_description, rl.news_id',
    //         'report_list rl',
    //         'rl.news_id',
    //         $news_ids
    //     );

    //     if (empty($reports)) return [];

    //     $report_ids = array_column($reports, 'report_id');
    //     $news_ids = array_unique(array_column($reports, 'news_id'));

    //     // مرحله ۳: گرفتن رسانه‌های گزارش
    //     $media_report = $this->safe_where_in_query(
    //         'mr.target_id AS report_id, m.id, m.url, m.type',
    //         'media_relation mr',
    //         'mr.target_id',
    //         $report_ids,
    //         function($db) {
    //             $db->join('media m', 'm.id = mr.media_id');
    //             $db->where('mr.target_table', 'report_list');
    //         }
    //     );
    //     $mediaReportMap = [];
    //     foreach ($media_report as $row) {
    //         $mediaReportMap[$row['report_id']][] = $row;
    //     }

    //     // مرحله ۴: گرفتن رسانه‌های خبر
    //     $media_news = $this->safe_where_in_query(
    //         'mr.target_id AS news_id, m.id, m.url, m.type',
    //         'media_relation mr',
    //         'mr.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('media m', 'm.id = mr.media_id');
    //             $db->where('mr.target_table', 'news');
    //         }
    //     );
    //     $mediaNewsMap = [];
    //     foreach ($media_news as $row) {
    //         $mediaNewsMap[$row['news_id']][] = $row;
    //     }

    //     // مرحله ۵: گرفتن دسته‌بندی‌ها
    //     $categories = $this->safe_where_in_query(
    //         'cr.target_id AS news_id, c.id, c.title',
    //         'category_relation cr',
    //         'cr.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('category c', 'c.id = cr.category_id');
    //             $db->where('cr.target_table', 'news');
    //         }
    //     );
    //     $categoryMap = [];
    //     foreach ($categories as $cat) {
    //         $categoryMap[$cat['news_id']][] = [
    //             'id' => $cat['id'],
    //             'title' => $cat['title']
    //         ];
    //     }

    //     // مرحله ۶: گرفتن آدرس‌های خبر
    //     $addresses = $this->safe_where_in_query(
    //         'ar.target_id as news_id, a.city, a.address, a.lat, a.lon',
    //         'address_relation ar',
    //         'ar.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('addresses a', 'a.id = ar.address_id');
    //             $db->where('ar.target_table', 'news');
    //         }
    //     );
    //     $addressMap = array_column($addresses, null, 'news_id');

    //     // مرحله ۷: گرفتن اطلاعات گزارش‌دهنده
    //     $reporters = $this->safe_where_in_query(
    //         'uar.target_id as report_id, u.name, u.family, um.phone, m.url as user_image_url, ua.id as user_account_id',
    //         'user_account_relations uar',
    //         'uar.target_id',
    //         $report_ids,
    //         function($db) {
    //             $db->join('user_account ua', 'ua.id = uar.user_account_id');
    //             $db->join('user_mobile um', 'um.id = ua.user_mobile_id');
    //             $db->join('users u', 'u.id = um.user_id');
    //             $db->join('media m', 'm.id = um.image_id', 'left');
    //             $db->where('uar.target_table', 'report_list');
    //         }
    //     );
    //     $reporterMap = [];
    //     foreach ($reporters as $row) {
    //         $reporterMap[$row['report_id']] = $row;
    //     }
    //     // مرحله ۸: گرفتن اطلاعات کاربر سازنده خبر
    //     $owners = $this->safe_where_in_query(
    //         'uar.target_id as news_id, u.name, u.family, um.phone, m.url as user_image_url, ua.id as user_account_id',
    //         'user_account_relations uar',
    //         'uar.target_id',
    //         $news_ids,
    //         function($db) {
    //             $db->join('user_account ua', 'ua.id = uar.user_account_id');
    //             $db->join('user_mobile um', 'um.id = ua.user_mobile_id');
    //             $db->join('users u', 'u.id = um.user_id');
    //             $db->join('media m', 'm.id = um.image_id', 'left');
    //             $db->where('uar.target_table', 'news');
    //         }
    //     );
    //     $newsOwnerMap = [];
    //     foreach ($owners as $row) {
    //         $newsOwnerMap[$row['news_id']] = $row;
    //     }
    //     // مرحله نهایی: ترکیب داده‌ها
    //     foreach ($reports as &$report) {
    //         $report_id = $report['report_id'];
    //         $news_id = $report['news_id'];
    //         $report['user_account_id'] = $newsOwnerMap[$news_id]['user_account_id'] ?? null;
    //         $report['user_name'] = $newsOwnerMap[$news_id]['name'] ?? null;
    //         $report['user_family'] = $newsOwnerMap[$news_id]['family'] ?? null;
    //         $report['user_phone'] = $newsOwnerMap[$news_id]['phone'] ?? null;
    //         $report['user_image_url'] = $newsOwnerMap[$news_id]['user_image_url'] ?? null;
    //         $report['news_privacy'] = $newsMap[$news_id]['privacy'] ?? null;
    //         $report['news_description'] = $newsMap[$news_id]['description'] ?? null;
    //         $report['news_status'] = $newsMap[$news_id]['status'] ?? null;
    //         $report['report_media'] = $mediaReportMap[$report_id] ?? [];
    //         $report['news_media'] = $mediaNewsMap[$news_id] ?? [];
    //         $report['category'] = $categoryMap[$news_id] ?? [];
    //         $report['address'] = $addressMap[$news_id] ?? [];
    //         $report['reporter'] = [
    //             'user_account_id' => $reporterMap[$report_id]['user_account_id'] ?? null,
    //             'name' => $reporterMap[$report_id]['name'] ?? null,
    //             'family' => $reporterMap[$report_id]['family'] ?? null,
    //             'phone' => $reporterMap[$report_id]['phone'] ?? null,
    //             'user_image_url' => $reporterMap[$report_id]['user_image_url'] ?? null,
    //         ];
    //     }

    //     return $reports;
    // }