<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    public Category_model $category_model;
    public Media_model $media_model;
    public News_model $news_model;
    public Notification_model $notification_model;
    public Users_model $users_model;
    public Wallet_model $wallet_model;
    public Rule_model $rule_model;
    public Report_model $report_model;
    public Order_model $order_model;
    public Place_model $place_model;

    public Send_handler $send_handler;
    public Functions_handler $functions_handler;
    public Security_handler $security_handler;
    public Finger_print $finger_print;
    public Upload_handler $upload_handler;
    public Login_handler $login_handler;
    public User_handler $user_handler;
    public News_handler $news_handler;
    public Wallet_handler $wallet_handler;
    public Place_handler $place_handler;
    public Api_handler $api_handler;
    public Category_handler $category_handler;
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        require_once(APPPATH . 'libraries/Tools/Send_handler.php');
        require_once(APPPATH . 'libraries/Tools/Functions_handler.php');
        require_once(APPPATH . 'libraries/Tools/Security_handler.php');
        require_once(APPPATH . 'libraries/Main/Login/Finger_print.php');
        require_once(APPPATH . 'libraries/Tools/Upload_handler.php');
        require_once(APPPATH . 'libraries/Main/Login/Login_handler.php');
        require_once(APPPATH . 'libraries/Main/Dashboard/Category_handler.php');
        require_once(APPPATH . 'libraries/Main/Dashboard/User_handler.php');
        require_once(APPPATH . 'libraries/Main/Dashboard/News_handler.php');
        require_once(APPPATH . 'libraries/Main/Dashboard/Wallet_handler.php');
        require_once(APPPATH . 'libraries/Main/Dashboard/Place_handler.php');
        require_once(APPPATH . 'libraries/Api_handler.php');

        $this->model('Category_model', 'category_model');
        $this->model('Media_model', 'media_model');
        $this->model('News_model', 'news_model');
        $this->model('Notification_model', 'notification_model');
        $this->model('Users_model', 'users_model');
        $this->model('Wallet_model', 'wallet_model');
        $this->model('Rule_model', 'rule_model');
        $this->model('Report_model', 'report_model');
        $this->model('Order_model', 'order_model');
        $this->model('Place_model', 'place_model');
        

        $this->category_model = $CI->category_model;
        $this->media_model = $CI->media_model;
        $this->news_model = $CI->news_model;
        $this->notification_model = $CI->notification_model;
        $this->users_model = $CI->users_model;
        $this->wallet_model = $CI->wallet_model;
        $this->rule_model = $CI->rule_model;
        $this->report_model = $CI->report_model;
        $this->order_model = $CI->order_model;
        $this->place_model = $CI->place_model;

        $this->send_handler= new Send_handler();
        $this->security_handler= new Security_handler();
        $this->finger_print= new Finger_print();
        $this->user_handler= new User_handler(
            $this->security_handler,
            $this->rule_model,
            $this->users_model,
            $this->media_model,
            $this->notification_model,
            $this->send_handler
        );
        // $this->wallet_model,
        // $this->media_model,
        // $this->news_model,
        $this->functions_handler= new Functions_handler(
            $this->user_handler,
            $this->send_handler,
            $this->category_model,
            $this->users_model,
            $this->notification_model,
            $this->rule_model,
        );
        $this->news_handler= new News_handler(
            $this->security_handler,
            $this->user_handler,
            $this->send_handler,
            $this->functions_handler,
            $this->category_model,
            $this->news_model,
            $this->notification_model,
            $this->users_model,
            $this->media_model,
            $this->report_model
        );
        $this->wallet_handler= new Wallet_handler(
            $this->security_handler,
            $this->order_model,
            $this->user_handler,
            $this->users_model,
            $this->wallet_model,
            $this->notification_model,
        );
        $this->upload_handler= new Upload_handler(
            $this->security_handler,
            $this->media_model
        );
        $this->login_handler= new Login_handler(
            $this->send_handler, 
            $this->security_handler, 
            $this->finger_print,
            $this->user_handler,
            $this->users_model,
            $this->media_model
        );
        $this->place_handler=new Place_handler(
            $this->user_handler,
            $this->place_model,
            $this->security_handler,
            $this->functions_handler,
            $this->send_handler
        );
        $this->category_handler=new Category_handler(
            $this->user_handler,
            $this->functions_handler,
            $this->category_model
        );
        $this->api_handler= new Api_handler(
            $this->upload_handler,
            $this->security_handler,
            $this->login_handler,
            $this->user_handler,
            $this->news_handler,
            $this->wallet_handler,
            $this->place_handler,
            $this->category_handler
        );
        // $CI->category_model=$this->category_model;
        // $CI->media_model=$this->media_model;
        // $CI->news_model=$this->news_model;
        // $CI->notification_model=$this->notification_model;
        // $CI->users_model=$this->users_model;
        // $CI->wallet_model=$this->wallet_model;
        // $CI->rule_model = $this->rule_model;

        // $CI->send_handler=$this->send_handler;
        // $CI->functions_handler=$this->functions_handler;
        $CI->security_handler=$this->security_handler;
        $CI->category_handler=$this->category_handler;
        $CI->finger_print=$this->finger_print;
        $CI->upload_handler=$this->upload_handler;
        $CI->login_handler=$this->login_handler;
        $CI->user_handler=$this->user_handler;
        $CI->news_handler=$this->news_handler;
        $CI->wallet_handler=$this->wallet_handler;
        $CI->place_handler=$this->place_handler;
        $CI->api_handler=$this->api_handler;
    }
}
