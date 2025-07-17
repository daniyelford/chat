<?php


class News_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
	}
	private $tbl="news";
    private $report="report_list";
    private $category_relation='category_news';
    public $user_lat=0;
    public $user_lon=0;
    private function select_where_array_table($tbl,$arr){
	    return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr)?$this->db->get_where($tbl,$arr)->result_array():false);
	}
	private function select_where_id_table($tbl,$id){
	    return (!empty($tbl) && is_string($tbl) && !empty($id) && intval($id)>0?$this->select_where_array_table($tbl,['id'=>intval($id)]):false);
	}
    private function add_to_table_return_id($tbl,$arr){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && $this->db->insert($tbl,$arr)?$this->db->insert_id():false);
    }
    private function edit_table($tbl,$arr,$where){
        return (!empty($tbl) && is_string($tbl) && !empty($arr) && is_array($arr) && !empty($where) && is_array($where) && $this->db->update($tbl,$arr,$where));
    }
    // costum
    // news
    public function get_private_checking_news_by_category($ids, $limit = 10, $offset = 0) {
        $this->db->from('news n');
        $this->db->join('category_relation cr', 'cr.target_id = n.id AND cr.target_table = "news"');
        $this->db->where_in('cr.category_id', $ids);
        $this->db->where('n.status', 'checking');
        $this->db->where('n.privacy', 'private');
        $total = $this->db->count_all_results();
        $this->db->select('
            n.*,
            a.city AS city,
            a.address AS address,
            a.lat AS address_lat,
            a.lon AS address_lon,
            c.title AS category_title,
            uar.user_account_id,
            um.phone AS user_phone,
            u.name AS user_name,
            u.family AS user_family,
            user_img.url AS user_image_url
        ');
        if ($this->user_lat !== null && $this->user_lon !== null) {
            $this->db->select("(
                6371 * acos(
                    cos(radians($this->user_lat)) *
                    cos(radians(a.lat)) *
                    cos(radians(a.lon) - radians($this->user_lon)) +
                    sin(radians($this->user_lat)) *
                    sin(radians(a.lat))
                )
            ) AS distance");
            $this->db->order_by('distance', 'ASC');
        }
        $this->db->from('news n');
        $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
        $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
        $this->db->join('category_relation cr', 'cr.target_id = n.id AND cr.target_table = "news"');
        $this->db->join('category c', 'c.id = cr.category_id');
        $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "news"', 'left');
        $this->db->join('user_account ua', 'ua.id = uar.user_account_id', 'left');
        $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
        $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
        $this->db->join('users u', 'u.id = um.user_id', 'left');
        $this->db->where_in('cr.category_id', $ids);
        $this->db->where('n.status', 'checking');
        $this->db->where('n.privacy', 'private');
        $this->db->order_by('cr.category_id', 'ASC');
        $this->db->order_by('n.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $news = $this->db->get()->result_array();
        $news_ids = array_column($news, 'id');
        if (!empty($news_ids)) {
            $this->db->select('mr.target_id AS news_id, m.url, m.type');
            $this->db->from('media_relation mr');
            $this->db->join('media m', 'm.id = mr.media_id');
            $this->db->where('mr.target_table', 'news');
            $this->db->where_in('mr.target_id', $news_ids);
            $media = $this->db->get()->result_array();
            $media_map = [];
            foreach ($media as $m) {
                $media_map[$m['news_id']][] = $m;
            }
            foreach ($news as &$n) {
                $n['media'] = $media_map[$n['id']] ?? [];
            }
        }
        return ['data' => $news, 'count_all' => $total];
    }
    // public function get_public_checking_news($limit = 10, $offset = 0) {
    //     $this->db->from('news n');
    //     $this->db->where('n.status', 'checking');
    //     $this->db->where('n.privacy', 'public');
    //     $total = $this->db->count_all_results();
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
    //     if ($this->user_lat !== null && $this->user_lon !== null) {
    //         $this->db->select("(
    //             6371 * acos(
    //                 cos(radians($this->user_lat)) *
    //                 cos(radians(a.lat)) *
    //                 cos(radians(a.lon) - radians($this->user_lon)) +
    //                 sin(radians($this->user_lat)) *
    //                 sin(radians(a.lat))
    //             )
    //         ) AS distance");
    //         $this->db->order_by('distance', 'ASC');
    //     }
    //     $this->db->from('news n');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "news"', 'left');
    //     $this->db->join('user_account ua', 'ua.id = uar.user_account_id', 'left');
    //     $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
    //     $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
    //     $this->db->join('users u', 'u.id = um.user_id', 'left');
    //     $this->db->where('n.status', 'checking');
    //     $this->db->where('n.privacy', 'public');
    //     $this->db->order_by('n.created_at', 'DESC');
    //     $this->db->limit($limit, $offset);
    //     $newsList = $this->db->get()->result_array();
    //     $newsIds = array_column($newsList, 'id');
    //     $categoryMap = $mediaList = [];
    //     if (!empty($newsIds)) {
    //         $this->db->select('cr.target_id AS news_id, c.id AS category_id, c.title AS category_title');
    //         $this->db->from('category_relation cr');
    //         $this->db->join('category c', 'c.id = cr.category_id');
    //         $this->db->where('cr.target_table', 'news');
    //         $this->db->where_in('cr.target_id', $newsIds);
    //         $categories = $this->db->get()->result();
    //         foreach ($categories as $cat) {
    //             $categoryMap[$cat->news_id][] = [
    //                 'id' => $cat->category_id,
    //                 'title' => $cat->category_title
    //             ];
    //         }
    //         $this->db->select('mr.target_id AS news_id, m.url, m.type');
    //         $this->db->from('media_relation mr');
    //         $this->db->join('media m', 'm.id = mr.media_id');
    //         $this->db->where('mr.target_table', 'news');
    //         $this->db->where_in('mr.target_id', $newsIds);
    //         $results = $this->db->get()->result();
    //         foreach ($results as $media) {
    //             $mediaList[$media->news_id][] = [
    //                 'url' => $media->url,
    //                 'type' => $media->type,
    //             ];
    //         }
    //     }
    //     foreach ($newsList as &$news) {
    //         $news['category'] = $categoryMap[$news['id']] ?? [];
    //         $news['media'] = $mediaList[$news['id']] ?? [];
    //     }
    //     return ['data' => $newsList, 'count_all' => $total];
    // }
    public function get_public_checking_news($limit = 10, $offset = 0)
    {
        // 1. فقط id ها و created_at بگیر
        $this->db->select('id');
        $this->db->from('news');
        $this->db->where('status', 'checking');
        $this->db->where('privacy', 'public');
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        $newsRaw = $this->db->get()->result_array();

        $newsIds = array_column($newsRaw, 'id');

        if (empty($newsIds)) {
            return ['data' => [], 'count_all' => 0];
        }

        // 2. حالا اطلاعات کامل برای idها بگیر (bulk)
        $newsDetails = $this->get_news_details_by_ids($newsIds);

        // 3. دسته‌بندی و مدیا رو جدا بگیر
        $categories = $this->get_news_categories($newsIds);
        $media = $this->get_news_media($newsIds);

        // 4. ترکیب نهایی
        foreach ($newsDetails as &$n) {
            $n['category'] = $categories[$n['id']] ?? [];
            $n['media'] = $media[$n['id']] ?? [];
        }

        // 5. شمارش کل برای صفحه اول
        // $count_all = null;
        // if ($offset === 0) {
            $this->db->from('news');
            $this->db->where('status', 'checking');
            $this->db->where('privacy', 'public');
            $count_all = $this->db->count_all_results();
        // }

        return ['data' => array_reverse(array_values($newsDetails)), 'count_all' => $count_all];
    }
    protected function get_news_details_by_ids(array $newsIds): array
    {
        $this->db->select('
            n.id,
            n.description,
            n.created_at,
            a.city,
            a.address,
            a.lat AS address_lat,
            a.lon AS address_lon,
            uar.user_account_id,
            um.phone AS user_phone,
            u.name AS user_name,
            u.family AS user_family,
            user_img.url AS user_image_url
        ');
        $this->db->from('news n');
        $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
        $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
        $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "news"', 'left');
        $this->db->join('user_account ua', 'ua.id = uar.user_account_id', 'left');
        $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
        $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
        $this->db->join('users u', 'u.id = um.user_id', 'left');
        $this->db->where_in('n.id', $newsIds);
        $result = $this->db->get()->result_array();

        $map = [];
        foreach ($result as $r) {
            $map[$r['id']] = $r;
        }

        return $map;
    }
    protected function get_news_categories(array $newsIds): array
    {
        if (empty($newsIds)) return [];

        $this->db->select('cr.target_id AS news_id, c.id AS category_id, c.title AS category_title');
        $this->db->from('category_relation cr');
        $this->db->join('category c', 'c.id = cr.category_id');
        $this->db->where('cr.target_table', 'news');
        $this->db->where_in('cr.target_id', $newsIds);
        $result = $this->db->get()->result();

        $map = [];
        foreach ($result as $row) {
            $map[$row->news_id][] = [
                'id' => $row->category_id,
                'title' => $row->category_title
            ];
        }

        return $map;
    }
    protected function get_news_media(array $newsIds): array
    {
        if (empty($newsIds)) return [];

        $this->db->select('mr.target_id AS news_id, m.url, m.type');
        $this->db->from('media_relation mr');
        $this->db->join('media m', 'm.id = mr.media_id');
        $this->db->where('mr.target_table', 'news');
        $this->db->where_in('mr.target_id', $newsIds);
        $result = $this->db->get()->result();

        $map = [];
        foreach ($result as $row) {
            $map[$row->news_id][] = [
                'url' => $row->url,
                'type' => $row->type
            ];
        }

        return $map;
    }


    public function get_news_by_id($id) {
        $this->db->select('
            n.*,
            a.id AS address_id,
            a.city AS city,
            a.address AS address,
            a.lat AS address_lat,
            a.lon AS address_lon
        ');
        $this->db->from('news n');
        $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
        $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
        $this->db->where('n.id', $id);
        $news = $this->db->get()->row_array();
        if (!$news) return null;
        $this->db->select('user_account_id');
        $this->db->from('user_account_relations');
        $this->db->where('target_table', 'news');
        $this->db->where('target_id', $id);
        $relation = $this->db->get()->row_array();
        if ($relation && isset($relation['user_account_id'])) {
            $user_account_id = $relation['user_account_id'];
            $this->db->select('
                ua.id AS user_account_id,
                um.phone AS user_phone,
                u.name AS user_name,
                u.family AS user_family,
                media.url AS user_image_url
            ');
            $this->db->from('user_account ua');
            $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
            $this->db->join('users u', 'u.id = um.user_id', 'left');
            $this->db->join('media', 'media.id = um.image_id', 'left');
            $this->db->where('ua.id', $user_account_id);
            $user_info = $this->db->get()->row_array();
            $news = array_merge($news, $user_info ?? []);
        } else {
            $news = array_merge($news, [
                'user_account_id' => null,
                'user_phone' => null,
                'user_name' => null,
                'user_family' => null,
                'user_image_url' => null,
            ]);
        }
        $this->db->select('c.id, c.title');
        $this->db->from('category_relation cr');
        $this->db->join('category c', 'c.id = cr.category_id');
        $this->db->where('cr.target_table', 'news');
        $this->db->where('cr.target_id', $id);
        $news['categories'] = $this->db->get()->result_array();
        $this->db->select('m.id, m.url, m.type');
        $this->db->from('media_relation mr');
        $this->db->join('media m', 'm.id = mr.media_id');
        $this->db->where('mr.target_table', 'news');
        $this->db->where('mr.target_id', $id);
        $news['media'] = $this->db->get()->result_array();
        return $news;
    }
    // public function get_news_by_user_account_id($user_account_id) {
    //     $this->db->select('target_id AS news_id');
    //     $this->db->from('user_account_relations');
    //     $this->db->where('user_account_id', $user_account_id);
    //     $this->db->where('target_table', 'news');
    //     $news_ids_result = $this->db->get()->result_array();
    //     if (empty($news_ids_result)) return [];
    //     $news_ids = array_column($news_ids_result, 'news_id');
    //     $this->db->select('
    //         n.*,
    //         a.id AS address_id,
    //         a.city AS city,
    //         a.address AS address,
    //         a.lat AS address_lat,
    //         a.lon AS address_lon
    //     ');
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
    //     $this->db->from('news n');
    //     $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
    //     $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
    //     $this->db->where_in('n.id', $news_ids);
    //     $news_list = $this->db->get()->result_array();
    //     $mediaMap = [];
    //     if(!empty($medias))
    //         foreach ($medias as $media) {
    //             $mediaMap[$media['news_id']][] = [
    //                 'id' => $media['id'],
    //                 'url' => $media['url'],
    //                 'type' => $media['type']
    //             ];
    //         }
    //     if(!empty($news_list))
    //         foreach ($news_list as &$news) {
    //             $id = $news['id'];
    //             $news['category'] = $categoryMap[$id] ?? [];
    //             $news['media'] = $mediaMap[$id] ?? [];
    //         }
    //     return $news_list;
    // }
    public function get_news_by_user_account_id($user_account_id) {
        // گام اول: گرفتن لیست خبرها با آدرس‌ها
        $this->db->select('
            n.*,
            a.id AS address_id,
            a.city AS city,
            a.address AS address,
            a.lat AS address_lat,
            a.lon AS address_lon
        ');
        $this->db->from('news n');
        $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "news"', 'inner');
        $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
        $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
        $this->db->where('uar.user_account_id', $user_account_id);
        $news_list = $this->db->get()->result_array();

        if (empty($news_list)) {
            return [];
        }

        // گام دوم: گرفتن IDهای خبر برای category و media
        $news_ids = array_column($news_list, 'id');

        // گام سوم: گرفتن دسته‌بندی‌ها با تقسیم‌بندی برای جلوگیری از حجم زیاد
        $categories = [];
        foreach (array_chunk($news_ids, 500) as $chunk) {
            $result = $this->db
                ->select('cr.target_id AS news_id, c.id, c.title')
                ->from('category_relation cr')
                ->join('category c', 'c.id = cr.category_id')
                ->where('cr.target_table', 'news')
                ->where_in('cr.target_id', $chunk)
                ->get()
                ->result_array();
            $categories = array_merge($categories, $result);
        }

        // مپ کردن categoryها
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['news_id']][] = [
                'id' => $cat['id'],
                'title' => $cat['title']
            ];
        }

        // گام چهارم: گرفتن رسانه‌ها با batching
        $medias = [];
        foreach (array_chunk($news_ids, 500) as $chunk) {
            $result = $this->db
                ->select('mr.target_id AS news_id, m.id, m.url, m.type')
                ->from('media_relation mr')
                ->join('media m', 'm.id = mr.media_id')
                ->where('mr.target_table', 'news')
                ->where_in('mr.target_id', $chunk)
                ->get()
                ->result_array();
            $medias = array_merge($medias, $result);
        }

        // مپ کردن mediaها
        $mediaMap = [];
        foreach ($medias as $media) {
            $mediaMap[$media['news_id']][] = [
                'id' => $media['id'],
                'url' => $media['url'],
                'type' => $media['type']
            ];
        }

        // گام پنجم: ترکیب همه اطلاعات در خروجی نهایی
        foreach ($news_list as &$news) {
            $id = $news['id'];
            $news['category'] = $categoryMap[$id] ?? [];
            $news['media'] = $mediaMap[$id] ?? [];
        }

        return $news_list;
    }
    public function get_news_by_user_account_ids(array $user_account_ids){
        if (empty($user_account_ids)) return [];
        $this->db->select('target_id');
        $this->db->from('user_account_relations');
        $this->db->where_in('user_account_id', $user_account_ids);
        $this->db->where('target_table', 'news');
        $news_ids_relation = $this->db->get()->result_array();
        if (empty($news_ids_relation)) {
            return [];
        }
        $news_ids = array_unique(array_column($news_ids_relation, 'target_id'));
        $this->db->select('
            n.*,
            a.city AS city,
            a.address AS address,
            a.lat AS address_lat,
            a.lon AS address_lon,
            uar.user_account_id,
            um.phone AS user_phone,
            u.name AS user_name,
            u.family AS user_family,
            user_img.url AS user_image_url
        ');
        $this->db->from('news n');
        $this->db->join('address_relation ar', 'ar.target_id = n.id AND ar.target_table = "news"', 'left');
        $this->db->join('addresses a', 'a.id = ar.address_id', 'left');
        $this->db->join('user_account_relations uar', 'uar.target_id = n.id AND uar.target_table = "news"', 'left');
        $this->db->join('user_account ua', 'ua.id = uar.user_account_id', 'left');
        $this->db->join('user_mobile um', 'um.id = ua.user_mobile_id', 'left');
        $this->db->join('media user_img', 'user_img.id = um.image_id', 'left');
        $this->db->join('users u', 'u.id = um.user_id', 'left');
        $this->db->where_in('n.id', $news_ids);
        $news_list = $this->db->get()->result_array();
        if (empty($news_list)) return [];
        $this->db->select('cr.target_id AS news_id, c.id, c.title');
        $this->db->from('category_relation cr');
        $this->db->join('category c', 'c.id = cr.category_id');
        $this->db->where('cr.target_table', 'news');
        $this->db->where_in('cr.target_id', $news_ids);
        $categories = $this->db->get()->result_array();
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['news_id']][] = [
                'id' => $cat['id'],
                'title' => $cat['title']
            ];
        }
        $this->db->select('mr.target_id AS news_id, m.id, m.url, m.type');
        $this->db->from('media_relation mr');
        $this->db->join('media m', 'm.id = mr.media_id');
        $this->db->where('mr.target_table', 'news');
        $this->db->where_in('mr.target_id', $news_ids);
        $medias = $this->db->get()->result_array();
        $mediaMap = [];
        foreach ($medias as $media) {
            $mediaMap[$media['news_id']][] = [
                'id' => $media['id'],
                'url' => $media['url'],
                'type' => $media['type']
            ];
        }
        foreach ($news_list as &$news) {
            $id = $news['id'];
            $news['category'] = $categoryMap[$id] ?? [];
            $news['media'] = $mediaMap[$id] ?? [];
        }
        return $news_list;
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
    public function select_news_where_category_id_status_checking_private($id){
        if (!empty($id) && is_numeric($id)) {
            $news=$this->select_where_array_table($this->category_relation,['category_id'=>$id]);
            $news_array_id=[];
            if(!empty($news))
                foreach($news as $a){
                    if(!empty($a) && !empty($a['news_id']) && intval($a['news_id'])>0)
                        $news_array_id[]=intval($a['news_id']);
                }
            if(!empty($news_array_id)){
                $this->db->from($this->tbl);
                $this->db->group_start();
                $this->db->where('status', 'checking');
                $this->db->where('privacy', 'private');
                $this->db->where_in('id', $news_array_id);
                $this->db->group_end();
                return $this->db->get()->result_array();
            }
        }
        return [];
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
    public function seen_weher_id_and_user_account_id($id){
        return (!empty($id) && intval($id)>0 && $this->edit_table($this->tbl,['status'=>'seen'],['id'=>intval($id)]));
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