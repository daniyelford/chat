<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsSeeder extends CI_Controller {
    public function index() {
        $this->load->database();
        $this->news();
        $this->category();
        $this->media();
        $this->user();
        $this->address();
    }
    private function news(){
        $newsData = [];
        for ($i = 1; $i <= 6; $i++) {
            $newsData[] = [
                'description' => "توضیحات خبر شماره $i",
                'privacy'=>($i>4?'private':'public'),
            ];
        }
        $this->db->insert_batch('news', $newsData);
    }
    private function category(){
        $catNewsData = [];
        for ($i = 1; $i <= 6; $i++) {
            $catNewsData[] = [
                'target_table'=>'news',
                'target_id'=>$i,
                'category_id'=>1
            ];
        }
        $this->db->insert_batch('category_relation', $catNewsData);
    }
    private function media(){
        $mediadata = [];
        for ($i = 1; $i <= 6; $i++) {
            $mediadata[] = [
                'target_table'=>'news',
                'target_id'=>$i,
                'media_id'=>1
            ];
        }
        $this->db->insert_batch('media_relation', $mediadata);
    }
    private function user(){
        $user_account = [];
        for ($i = 1; $i <= 6; $i++) {
            $user_account[] = [
                'target_table'=>'news',
                'target_id'=>$i,
                'user_account_id'=>($i>2?($i>4?3:2):1)
            ];
        }
        $this->db->insert_batch('user_account_relations', $user_account);
    }
    private function address(){
        $address_news = [];
        for ($i = 1; $i <= 6; $i++) {
            $address_news[] = [
                'target_table'=>'news',
                'target_id'=>$i,
                'address_id'=>1
            ];
        }
        $this->db->insert_batch('address_relation', $address_news);
    }
}
