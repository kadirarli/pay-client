<?php
class PayTrekApi extends Api{
    //Hatalı//Method isimlendirmeleri camelCase olmalı.
    public function __construct(){
        parent::__construct();
    }
    /**
     * PayTrek Api'sini temsil eder.
     * @return  Array  Durum bilgisini verir.
     */
    static function apiStatus(){
        return array("success"=>"1");
        //return array("success"=>"0","error_message"=>Lang::get("messages.paymentGateWayDeactive"));
    }
    /**
     * PayTrek Api'sini temsil eder.
     * @return  Array  Standart kur bilgisini verir.
     */
    static function apiCurrency(){
        return "usd";
    }
    /**
     * PayTrek Api'sini temsil eder.
     * @return  Array  Kur farkı bilgisini verir.
     */
    static function apiExchangeRate(){
        return 1.10;
    }
    /**
     * PayTrek Api'sini temsil eder.
     * @return  Array  Ödeme Bilgisini verir.
     */
    static function apiPay(){
        return array("success"=>"1");
    }
    function __destruct(){
    }
}