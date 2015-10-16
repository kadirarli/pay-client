<?php
class PaypalPayment{
     /**
     * Ödeme noktalarını kontrol eder.
     * @return  Array  Aktif olan ödeme noktaları
     */
    static function paymentGateWayStatus(){
        $paypalApiStatus = self::ApiStatus();
        return array("paypal"=>$paypalApiStatus);
    }
    /**
     * Kur farkı var ise, farka göre tekrar hesaplama yapmak için kullanılır.
     * @param   String  select_payment_gateway
     * @param   Int     text_value
     * @param   String  select_value_currency
     * @return  Int     Kur farkı uygulanmış yeni değer
     */
    static function checkCurrency($select_payment_gateway,$text_value,$select_value_currency){
    	$gatewayStatus = self::paymentGateWayStatus();
        $paypal_default_currency = PaypalApi::ApiCurrency();
		if($select_value_currency != $paypal_default_currency)
			$text_value = $text_value * PaypalApi::ApiExchangeRate();
        return array("success"=>"1","text_value"=>$text_value);
    }
    /**
     * Ödeme yapmak için kullanılır.
     * @param   String  text_name
     * @param   String  select_payment_gateway
     * @param   Int     text_value
     * @return  Array   Ödeme Durumu
     */
    static function pay($text_name,$select_payment_gateway,$text_value){
        //Hatalı//Her ödeme sisteminin ayrı sınıfı olduktan sonra if else lere ihtiyaç kalmayacak. Design Pattern ve OOP( abstract/interface )
        if($select_payment_gateway == "paypal")
            $gatewayStatus = PaypalApi::ApiStatus();
        elseif($select_payment_gateway == "payu")
            $gatewayStatus = PayUApi::ApiStatus();
        elseif($select_payment_gateway == "paytrek")
            $gatewayStatus = PayTrekApi::ApiStatus();
        if($gatewayStatus["success"] == "1"){

            if($select_payment_gateway == "paypal")
                $payStatus = PaypalApi::ApiPay();
            elseif($select_payment_gateway == "payu")
                $payStatus = PayUApi::ApiPay();
            elseif($select_payment_gateway == "paytrek")
                $payStatus = PayTrekApi::ApiPay();
            if($payStatus["success"]=="1")
                return array("success"=>"1");
            else
                return array("success"=>"0");
        }
        else
            return array("success"=>"0");
    }
    /**
     * Ödeme sonrası bilgilendirme maili atar.
     * @param   String  text_name
     * @param   Int     text_value
     * @param   String  select_value_currency
     * @return  Array   Mail durumu
     */
    static function sendVoucher($text_name,$text_value,$select_value_currency){
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