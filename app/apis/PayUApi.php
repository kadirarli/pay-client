<?php
class PayUApi extends Api{
    //Hatalı//Method isimlendirmeleri camelCase olmalı.
    public function __construct(){
        parent::__construct();
    }
    /**
     * PayU Api'sini temsil eder.
     * @return  Array  Durum bilgisini verir.
     */
    static function ApiStatus(){
        return array("success"=>"1");
        //return array("success"=>"0","error_message"=>Lang::get("messages.paymentGateWayDeactive"));
    }
    /**
     * PayU Api'sini temsil eder.
     * @return  Array  Standart kur bilgisini verir.
     */
    static function ApiCurrency(){
        return "try";
    }
    /**
     * PayU Api'sini temsil eder.
     * @return  Array  Kur farkı bilgisini verir.
     */
    static function ApiExchangeRate(){
        return 1.12;
    }
    /**
     * PayU Api'sini temsil eder.
     * @return  Array  Ödeme Bilgisini verir.
     */
    static function ApiPay(){
        return array("success"=>"1");
    }
    function __destruct(){
    }
}