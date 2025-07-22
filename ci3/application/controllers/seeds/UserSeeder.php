<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UserSeeder extends CI_Controller {
    public function index() {
        $this->load->database();
        $this->db->insert_batch('users', [
            [
                'name' => "danial",
                'family' => 'fard',
            ],
            [
                'name' => "dnil",
                'family' => 'frd',
            ],
            [
                'name' => "dnyl",
                'family' => 'faard',
            ],
        ]);
        $this->db->insert_batch('user_mobile',[
            [
                'user_id'=>1,
                'phone'=>'09123456789'
            ],
            [
                'user_id'=>2,
                'phone'=>'09198765432'
            ],
            [
                'user_id'=>3,
                'phone'=>'09336160295'
            ],
        ]);
        $this->db->insert_batch('user_account', [
            [
                'user_mobile_id' => 1,
            ],
            [
                'user_mobile_id' => 2,
            ],
            [
                'user_mobile_id' => 3,
            ]
        ]);
        $this->db->insert_batch('addresses',[
            [
                'address'=>'Van Beethovenlaan, Roosendaal, Noord-Brabant, Nede...',
                'country'=>'The Netherlands',
                'region'=>'North Brabant',
                'city'=>'Roosendaal',
                'lat'=>51.5359000,
                'lon'=>4.4653200,
                'currency'=>'EUR',
                'mobile'=>0,
                'proxy'=>1
            ],
            [
                'address'=>'Van Beethovenlaan, Roosendaal, Noord-Brabant, Nede...',
                'country'=>'The Netherlands',
                'region'=>'North Brabant',
                'city'=>'Roosendaal',
                'lat'=>51.5359000,
                'lon'=>4.4653200,
                'currency'=>'EUR',
                'mobile'=>0,
                'proxy'=>1
            ],
            [
                'address'=>'Van Beethovenlaan, Roosendaal, Noord-Brabant, Nede...',
                'country'=>'The Netherlands',
                'region'=>'North Brabant',
                'city'=>'Roosendaal',
                'lat'=>51.5359000,
                'lon'=>4.4653200,
                'currency'=>'EUR',
                'mobile'=>0,
                'proxy'=>1
            ],
        ]);
        $this->db->insert_batch('address_relation',[
            [
                'address_id'=>1,
                'target_table'=>'user_account',
                'target_id'=>1
            ],
            [
                'address_id'=>2,
                'target_table'=>'user_account',
                'target_id'=>2
            ],
            [
                'address_id'=>3,
                'target_table'=>'user_account',
                'target_id'=>3
            ],
        ]);
        echo 'ok';
    }	
}