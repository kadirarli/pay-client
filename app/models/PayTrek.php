<?php
class PayTrekPayment{
     /**
     * Ödeme noktalarını kontrol eder.
     * @return  Array  Aktif olan ödeme noktaları
     */
    static function paymentGateWayStatus(){
        $paypalApiStatus = PaypalApi::ApiStatus();
        $payUApiStatus = PayUApi::ApiStatus();
        $payTrekApiStatus = PayTrekApi::ApiStatus();
        return array("paypal"=>$paypalApiStatus,
            "payu"=>$payUApiStatus,
            "paytrek"=>$payTrekApiStatus);
    }
    /**
     * Kur farkı var ise, farka göre tekrar hesaplama yapmak için kullanılır.
     * @param   String  select_payment_gateway
     * @param   Int     text_value
     * @param   String  select_value_currency
     * @return  Int     Kur farkı uygulanmış yeni değer
     */
    static function checkCurrency($select_payment_gateway,$text_value,$select_value_currency){
        //Hatalı//Her ödeme sisteminin ayrı sınıfı olduktan sonra if else lere ihtiyaç kalmayacak. Design Pattern ve OOP( abstract/interface )
    	$paypal_default_currency = PaypalApi::ApiCurrency();
		$payu_default_currency = PayUApi::ApiCurrency();
		$paytrek_default_currency = PayTrekApi::ApiCurrency();

        $gatewayStatus = Payment::paymentGateWayStatus();

    	if(($select_payment_gateway == "paypal") && ($gatewayStatus["paypal"]["success"] == "1")){
    		if($select_value_currency != $paypal_default_currency){
    			$text_value = $text_value * PaypalApi::ApiExchangeRate();
    		}
    	}elseif(($select_payment_gateway == "payu") && ($gatewayStatus["payu"]["success"] == "1")){
    		if($select_value_currency != $payu_default_currency){
    			$text_value = $text_value * PayUApi::ApiExchangeRate();	
    		}
    	}elseif(($select_payment_gateway == "paytrek") && ($gatewayStatus["paytrek"]["success"] == "1")){
    		if($select_value_currency != $paytrek_default_currency){
    			$text_value = $text_value * PayTrekApi::ApiExchangeRate();
    		}
    	}else{
            return array("success"=>"0");
        }
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