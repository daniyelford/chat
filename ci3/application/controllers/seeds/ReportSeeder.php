<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportSeeder extends CI_Controller {
    public function index() {
        $this->report();
        $this->media();
        $this->user();
        $this->address();
    }
    private function report() {
        $reportData = [];
        for ($i = 3; $i <= 6; $i++) {
            $reportData[] = [
                'news_id'=>$i,
                'description' => "توضیحات report شماره $i",
            ];
        }
        $this->db->insert_batch('report_list', $reportData);
    }
    private function media(){
        $mediadata = [];
        for ($i = 1; $i <= 4; $i++) {
            // $i=$i+4;
            $mediadata[] = [
                'target_table'=>'report_list',
                'target_id'=>$i,
                'media_id'=>1
            ];
        }
        $this->db->insert_batch('media_relation', $mediadata);
    }
    private function user(){
        $user_account = [];
        for ($i = 1; $i <= 4; $i++) {
            // $i=$i+4;
            $user_account[] = [
                'target_table'=>'report_list',
                'target_id'=>$i,
                'user_account_id'=>1
            ];
        }
        $this->db->insert_batch('user_account_relations', $user_account);
    }
    private function address(){
        $address_news = [];
        for ($i = 1; $i <= 4; $i++) {
            // $i=$i+4;
            $address_news[] = [
                'target_table'=>'report_list',
                'target_id'=>$i,
                'address_id'=>1
            ];
        }
        $this->db->insert_batch('address_relation', $address_news);
    }
}
