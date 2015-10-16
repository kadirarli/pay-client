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
        //Bu kısım değişecek artık....
        //$gatewayStatus = Payment::paymentGateWayStatus();
		$selectBox = array();
        $selectBox = array_add($selectBox, 'paypal', 'Paypal');
		$selectBox = array_add($selectBox, 'payu', 'PayU');
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

        $className = "PaypalPayment";
        $class = new $className($text_name, $text_value, $select_value_currency);
        return $class->pay();

        $result = Payment::pay($text_name,$select_payment_gateway,$text_value);
	}
    function __destruct(){
    }
}