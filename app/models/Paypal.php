<?php
class Paypal extends Payment{ 
    function __construct($text_name, $text_value, $select_value_currency){
        $this->paymentApiName = "PaypalApi";
        $this->$text_name = $text_name; 
        $this->$text_value = $text_value;
        $this->$select_value_currency = $select_value_currency;
    } 
    public function checkApiStatus(){
    	$this->paymentApiName = "PaypalApi";
    	$className = $this->paymentApiName;
    	$class = new $className;
    	return $class::apiStatus();
    }
}