<?php
class PaymentController extends Controller{
    /**
     * Ödeme Sayfasını açmak için kullanılır.
     * @return  View  Ödeme Sayfası
     */
    public function paymentPage(){
        $gateWays = new GateWay;
        $gateWayList = $gateWays::get();
        $gateWayStatuses = array();
        foreach ($gateWayList as $key => $value) {
            $className = $value->className;
            $text_name = "default";
            $text_value = "0";
            $select_value_currency = "default";
            $class = new $className($text_name, $text_value, $select_value_currency);
            $status = $class->checkApiStatus();
            if($status["success"] == "1"){
                $gateWayStatuses = array_add($gateWayStatuses,$value->id,$value->description);
            }
        }
        $data = array("data"=>$gateWayStatuses);
    	return View::make('paymentPage',$data);
    }
    /**
     * Ödeme yapmak için kullanılır.
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
        }else{
            $text_name = Input::get("text_name");
            $select_payment_gateway = Input::get("select_payment_gateway");
            $text_value = Input::get("text_value");
            $select_value_currency = Input::get("select_value_currency");
            $gateWays = new GateWay;
            $gateWayList = $gateWays::where("id","=",$select_payment_gateway)->get();
            $class = new $gateWayList["0"]->className($text_name, $text_value, $select_value_currency);
            return $class->pay();
        }
	}
}