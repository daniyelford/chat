<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wallet_handler
{
  private Security_handler $security;
  private Order_model $order_model;
  private User_handler $user;
  private Users_model $users_model;
  private Wallet_model $wallet_model;
  private Notification_model $notification_model;
  public function __construct(
    Security_handler $security_handler,
    Order_model $order_model,
    User_handler $user_handler,
    Users_model $users_model,
    Wallet_model $wallet_model,
    Notification_model $notification_model 
  ){
    $this->security = $security_handler;
    $this->order_model = $order_model;
    $this->user = $user_handler;
    $this->users_model = $users_model;
    $this->wallet_model = $wallet_model;
    $this->notification_model = $notification_model;
	}
  public function get_cards() {
    return (!empty($this->user->get_user_account_id())? ['status' => 'success', 'data' => $this->wallet_model->select_carts_where_user_account_id($this->user->get_user_account_id())]:
    ['status' => 'error', 'message' => 'شناسه کاربر یافت نشد']);

  }
  public function add_card($data) {
    if(!empty($this->user->get_user_account_id()) && !empty($data) && (!empty($data['shomare_cart']) || !empty($data['shomare_hesab']) || !empty($data['shomare_shaba']))){
      $shaba=$this->security->string_security_check($data['shomare_shaba']);
      $cart=$this->security->string_security_check($data['shomare_cart']);
      $hesab=$this->security->string_security_check($data['shomare_hesab']);
      if (!($cart || $shaba || $hesab)) {
        return ['status' => 'error', 'message' => 'شماره کارت یا شبا نامعتبر است'];
      }
      $id = $this->wallet_model->add_cart_return_id([
        'shomare_shaba'=>$shaba??null,
        'shomare_hesab'=>$hesab??null,
        'shomare_cart'=>$cart??null,
      ]);
      $user_relation_id=$this->users_model->add_account_relations_return_id([
        'user_account_id'=>intval($this->user->get_user_account_id()),
        'target_table'=>'user_cart',
        'target_id'=>intval($id)
      ]);
      $this->wallet_model->add_cart_relation([
        'user_cart_id'=>intval($id),
        'target_table'=>'user_account_relations',
        'target_id'=>intval($user_relation_id)
      ]);
      return ['status'=>'success'];
    }
    return ['status'=>'error'];
  }
  public function delete_card($data) {
    return ($this->user->get_user_id() && !empty($data) &&
    !empty($data['id']) && intval($data['id'])>0 && 
    $this->wallet_model->remove_cart_where_id(intval($data['id']))?
    ['status'=>'success']:
    ['status'=>'error']);
  }
  public function request_withdrawal($data){
    $user_id = $this->user->get_user_account_id();
    $user_info=$this->user->get_user_info();
    $user_wallet=(!empty($user_info) && !empty($user_info['wallet'])?floatval($user_info['wallet']):0);
    $amount = floatval($data['amount'] ?? 0);
    $card_id = intval($data['card_id'] ?? 0);
    $result_money=$user_wallet-$amount;
    if ($user_wallet <= 0 || $result_money <= 0 || $amount > $user_wallet || intval($user_id) <= 0 || $amount <= 0 || $card_id <= 0) {
      return ['status' => 'error', 'message' => 'مبلغ یا کارت نامعتبر است'];
    }
    $user_id=intval($user_id);
    $card_relation = $this->wallet_model->check_card_belongs_to_user_account($card_id, $user_id);
    if (!$card_relation) {
      return ['status' => 'error', 'message' => 'کارت متعلق به شما نیست'];
    }
    $change_id=$this->wallet_model->add_account_changes_return_id([
      'new_balance'=>$result_money,
      'old_token'=>floatval($user_info['token']??0),
      'old_balance'=>$user_wallet,
      'new_token'=>floatval($user_info['token']??0)
    ]);
    $user_relation_id=$this->users_model->add_account_relations_return_id([
      'user_account_id'=>intval($this->user->get_user_account_id()),
      'target_table'=>'payment_account_changes',
      'target_id'=>intval($change_id)
    ]);
    $payement_id=$this->wallet_model->add_payement_return_id([
      'pay_money_user_account_relation_id'=>intval($user_relation_id),
      'give_money_user_account_relation_id'=>intval($user_relation_id),
      'amount'=>$amount,
      'pay_type'=>'withdrawal'
    ]);
    $this->wallet_model->add_cart_relation([
      'user_cart_id'=>intval($card_id),
      'target_table'=>'payment',
      'target_id'=>intval($payement_id)
    ]);
    $this->users_model->edit_account_weher_id(['balance'=>$result_money],$user_id);
    $notification_id=$this->notification_model->add_return_id([
      'title'=>'درخواست برداشت',
      'body'=>'درخواست شما برای برداشت وجه از کیف پولتان به مقدار'.number_format($amount).'تومان ثبت شد',
      'url'=>base_url('wallet'),
    ]);
    $this->users_model->add_account_relations([
      'user_account_id'=>intval($this->user->get_user_account_id()),
      'target_table'=>'notification',
      'target_id'=>intval($notification_id)
    ]);
    $this->user->reset_user_info_session();
    return ['status' => 'success'];
  }
 
  public function get_transactions($data) {
    return (!empty($data) && $this->user->get_user_account_id()?
    ['status' => 'success', 'data' => $this->order_model->get_grouped_payments_by_user_account(intval($this->user->get_user_account_id()),intval($data['limit']??10),intval($data['offset']??0))]:
    ['status' => 'error']);
  }
  public function get_discount_cards(){
    return ($this->user->get_user_account_id()?
    ['status'=>'success','data'=>$this->order_model->get_discount_products_by_user_account($this->user->get_user_account_id())]:
    ['status'=>'error']);
  }
  public function get_tokens(){
    return ($this->user->get_user_account_id()?
    ['status'=>'success','data'=>$this->order_model->get_report_tokens_by_news_owner(intval($this->user->get_user_account_id()))]:
    ['status'=>'error']);
  }
}