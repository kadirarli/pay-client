<?php

abstract class Payment{

    public $paymentApinName;
    public $text_name, $text_value, $select_value_currency;

    abstract protected function valueSet();

    public static function paymentGateWayStatus($paymentApi){
        $paypalApiStatus = $paymentApi::ApiStatus();
        return array("paypal"=>$paypalApiStatus);
    }

    public static function checkCurrency($paymentApi,$text_value,$select_value_currency){
        $paypal_default_currency = $paymentApi::ApiCurrency();
        $gatewayStatus = self::paymentGateWayStatus();
        if($gatewayStatus["success"] == "1"){
            if($select_value_currency != $paypal_default_currency){
                $text_value = $text_value * $paymentApi::ApiExchangeRate();
            }
        }else{
            return array("success"=>"0");
        }
        return array("success"=>"1","text_value"=>$text_value);
    }

    public static function pay(){
        $className = $this->$paymentApi;
        $class = new $className;
        $gatewayStatus = $class::ApiStatus();
        $payStatus = $class::ApiPay(); 
        if($payStatus["success"]=="1")
            return array("success"=>"1");
        else
            return array("success"=>"0");
    }
    public static function sendVoucher($text_name,$text_value,$select_value_currency){
        $time = Carbon\Carbon::now(new DateTimeZone('Europe/Istanbul'));
        $data = array("name"=>$text_value,"value"=>$text_value,"currency"=>$select_value_currency,"time"=>$time);
        Mail::send('emails.payment', $data, function($message){
            $message->to('accounting@testtest.com')->subject('Payment successful');
        });
        if(count(Mail::failures()) > 0){
            return array("success"=>"0");
        }
        return array("success"=>"1");
    }
}

class PaypalPayment extends Payment{
    function __construct($text_name, $text_value, $select_value_currency){
        $this->paymentApinName = "PaypalApi";
        $this->$text_name = $text_name; 
        $this->$text_value = $text_value;
        $this->$select_value_currency = $select_value_currency;
    }

    public function valueSet() {
        return "helloooooPaypal";
    }
}

class PayUPayment extends Payment{ 
    public function valueSet() {
        return "helloooooPayU";
    }
}

class PayTrekPayment extends Payment{
    public function valueSet() {
        return "helloooooPayTrek";
    }
}