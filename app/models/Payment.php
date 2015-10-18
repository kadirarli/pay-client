<?php
abstract class Payment{
    public $paymentApiName;
    public $text_name, $text_value, $select_value_currency;
    abstract public function checkApiStatus();
    public function checkCurrency(){
        $className = $this->paymentApiName;
        $class = new $className;
        $paypal_default_currency = $class::ApiCurrency();
        if($this->select_value_currency != $paypal_default_currency){
            $this->text_value = $this->text_value * $class::ApiExchangeRate();
        }else{
            return $this->text_value;
        }
    }
    public function pay(){
        $className = $this->paymentApiName;
        $class = new $className;
        $gatewayStatus = $class::ApiStatus();
        if($gatewayStatus["success"]=="1"){
            $new_value = $this->checkCurrency();
            $payStatus = $class::ApiPay();
            if($payStatus["success"]=="1"){
                $mailResult = $this->sendVoucher();
                if($mailResult["success"] == "1"){
                    return array("success"=>"1","success_message"=>Lang::get("messages.missionCompleted"));
                }else{
                    return $mailResult;
                }
            }else{
                return array("success"=>"0","error_message"=>Lang::get("messages.payFailed"));
            }
        }else{
            return array("success"=>"0","error_message"=>Lang::get("messages.paymentGateWayDeactive"));
        }
    }
    public function sendVoucher(){
        $time = Carbon\Carbon::now(new DateTimeZone('Europe/Istanbul'));
        $data = array("name"=>$this->text_value,"value"=>$this->text_value,"currency"=>$this->select_value_currency,"time"=>$time);
        Mail::send('emails.payment', $data, function($message){
            $message->to('accounting@testtest.com')->subject('Payment successful');
        });
        if(count(Mail::failures()) > 0){
            return array("success"=>"0","error_message"=>Lang::get("messages.mailFailed"));
        }else{
            return array("success"=>"1");    
        }
    }
}