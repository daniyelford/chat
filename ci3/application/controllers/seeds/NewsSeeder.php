<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsSeeder extends CI_Controller {
    
    public function index() {
        $this->load->database();
        $newsData = $mediadata = $catNewsData = [];
        for ($i = 1; $i <= 10000; $i++) {
            $newsData[] = [
                'description' => "توضیحات خبر شماره $i",
                'privacy'=>'public',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            if (count($newsData) >= 500) {
                $this->db->insert_batch('news', $newsData);
                $newsData = [];
            }
        }
        for ($i = 1; $i <= 10000; $i++) {
            $news_id = $i;
            $catNewsData[] = [
                'target_table'=>'news',
                'target_id'=>$news_id,
                'category_id'=>1
            ];
            if (count($catNewsData) >= 500) {
                $this->db->insert_batch('category_relation', $catNewsData);
                $catNewsData = [];
            }
        }
        for ($i = 1; $i <= 10000; $i++) {
            $news_id = $i;
            $mediadata[] = [
                'target_table'=>'news',
                'target_id'=>$news_id,
                'media_id'=>1
            ];
            if (count($mediadata) >= 500) {
                $this->db->insert_batch('media_relation', $mediadata);
                $mediadata = [];
            }
        }
        for ($i = 1; $i <= 10000; $i++) {
            $news_id = $i;
            $mediadata[] = [
                'target_table'=>'news',
                'target_id'=>$news_id,
                'user_account_id'=>1
            ];
            if (count($mediadata) >= 500) {
                $this->db->insert_batch('user_account_relations', $mediadata);
                $mediadata = [];
            }
        }
        echo "درج 10000 خبر و ارتباط با دسته‌ها انجام شد ✅";
    }
}
