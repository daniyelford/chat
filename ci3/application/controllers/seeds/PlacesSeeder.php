<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlacesSeeder extends CI_Controller {
    public function index() {
        $this->load->database();
        $this->place();
        $this->category();
        $this->media();
        $this->address();
    }
    private function place(){
        $data = [];
        for ($i = 1; $i <= 80; $i++) {
            $data[] = [
                'title'=>"مکان مورد تایید $i",
                'description' => "توضیحات خبر شماره $i",
            ];
        }
        $this->db->insert_batch('places', $data);
    }
    private function category(){
        $catNewsData = [];
        for ($i = 1; $i <= 80; $i++) {
            $catNewsData[] = [
                'target_table'=>'places',
                'target_id'=>$i,
                'category_id'=>rand(18,40)
            ];
        }
        $this->db->insert_batch('category_relation', $catNewsData);
    }
    private function media(){
        $mediadata = [];
        for ($i = 1; $i <= 80; $i++) {
            $mediadata[] = [
                'target_table'=>'places',
                'target_id'=>$i,
                'media_id'=>1
            ];
        }
        $this->db->insert_batch('media_relation', $mediadata);
    }
    private function address(){
        $address_news = [];
        for ($i = 1; $i <= 80; $i++) {
            $address_news[] = [
                'target_table'=>'places',
                'target_id'=>$i,
                'address_id'=>1
            ];
        }
        $this->db->insert_batch('address_relation', $address_news);
    }
}
