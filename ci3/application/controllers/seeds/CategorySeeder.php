<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategorySeeder extends CI_Controller {

    public function index() {
        $this->load->database();
        $this->no_place();
        $this->place();
    }
    public function place(){
        $data = [];
        $data[] = [
            'title' => "آموزشگاه",
            'for_place'=>'yes',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "خیاطی",
            'for_place'=>'yes',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert_batch('category', $data);
    }
    public function no_place(){
        $data = [];
        $data[] = [
            'title' => "پلیس امنیت",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "شهرداری",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "پلیس فتا",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "اداره آب و فاضلاب",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "اداره برق",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "اداره گاز",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "جهاد کشاورزی",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "استانداری",
            'for_place'=>'no',
            'is_force'=>'yes',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "اداره عمران",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "شورای شهر",
            'for_place'=>'no',
            'is_force'=>'yes',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "بانک مرکزی",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "وزارت کشور",
            'for_place'=>'no',
            'is_force'=>'yes',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "مخابرات",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "اداره پست",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "وزارت راه و شهر سازی",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "بنیاد برکت",
            'for_place'=>'no',
            'is_force'=>'no',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data[] = [
            'title' => "فرمانداری",
            'for_place'=>'no',
            'is_force'=>'yes',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        // $data[] = [
        //     'title' => "اداره ثبت",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "قوه قضاییه",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "اصناف",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "آموزش پرورش",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "جهاد دانشگاهی",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "سازمان وکلا",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "اتاق بازرگانی",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "بنیاد شهید و مستضعفان",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "خیریه",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "کمیته امداد",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "اداره مالیات",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "صنعت معدن تجارت",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "مجلس",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "نهاد ریاست جمهوری",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
        // $data[] = [
        //     'title' => "فناوری اطلاعات",
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];

        $this->db->insert_batch('category', $data);
        echo 'ok';
    }

}
