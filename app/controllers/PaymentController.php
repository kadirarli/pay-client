<?php
class PaymentController extends \BaseController{
    public function __construct(){
        parent::__construct();
    }
    /**
     * Ödeme Sayfasını açmak için kullanılır.
     * @return  View  Ödeme Sayfası
     */
    public function paymentPage(){
    	//Sayfa açıldığında aktif olan ödeme noktalarını göstermek için.
    	$gatewayStatus = Payment::paymentGateWayStatus();
    	$selectBox = array();
    	if($gatewayStatus["paypal"]["success"] == "1")
    		$selectBox = array_add($selectBox, 'paypal', 'Paypal');
    	if($gatewayStatus["payu"]["success"] == "1")
    		$selectBox = array_add($selectBox, 'payu', 'PayU');
    	if($gatewayStatus["paytrek"]["success"] == "1")
    		$selectBox = array_add($selectBox, 'paytrek', 'Paytrek');
    	$data = array("data"=>$selectBox);
    	return View::make('paymentPage',$data);
    }
    /**
     * Ödeme yapmak için kullanılır
     * @return  Array  Ödeme Son Durumu
     */
	public function pay(){
		$validator = Validator::make(Input::all(),[
			'text_name'  =>  'required',
			'select_payment_gateway'  =>  'required',
			'text_value'  =>  'required',
			'select_value_currency'  =>  'required'
			]);
        if($validator->fails()){
            $rows = $validator->messages();
            return $rows;
        }
        $text_name = Input::get("text_name");
        $select_payment_gateway = Input::get("select_payment_gateway");
        $text_value = Input::get("text_value");
        $select_value_currency = Input::get("select_value_currency");
        $new_value = Payment::checkCurrency($select_payment_gateway,$text_value,$select_value_currency);
        if($new_value["success"] == "0")
            return array("success"=>"0","error_message"=>Lang::get("messages.chooseAnotherGateway"));    
        $text_value = $new_value["text_value"];
        $result = Payment::pay($text_name,$select_payment_gateway,$text_value);
        if($result["success"] == "1"){
        	$mailControl = Payment::sendVoucher($text_name,$text_value,$select_value_currency);
        	if($mailControl["success"]){
        		return array("success"=>"1","success_message"=>Lang::get("messages.missionCompleted"));
        	}else{
        		return array("success"=>"0","error_message"=>Lang::get("messages.mailFailed"));
        	}
        }else{
        	return array("success"=>"0",
        		"error_message"=>Lang::get("messages.chooseAnotherGateway"));
        }
	}
    function __destruct(){
    }
}