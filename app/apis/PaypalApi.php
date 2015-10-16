<?php
class PaypalApi{
    /**
     * PayPal Api'sini temsil eder.
     * @return  Array  Durum bilgisini verir.
     */
    static function apiStatus(){
    	return array("success"=>"1");
        //return array("success"=>"0","error_message"=>Lang::get("messages.paymentGateWayDeactive"));
    }
    /**
     * PayPal Api'sini temsil eder.
     * @return  Array  Standart kur bilgisini verir.
     */
    static function apiCurrency(){
        return "eur";
    }
    /**
     * PayPal Api'sini temsil eder.
     * @return  Array  Kur farkı bilgisini verir.
     */
    static function apiExchangeRate(){
        return 1.08;
    }
    /**
     * PayPal Api'sini temsil eder.
     * @return  Array  Ödeme Bilgisini verir.
     */
    static function apiPay(){
        return array("success"=>"1");
    }
}