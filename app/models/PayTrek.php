<?php
class PayTrek extends Payment{
    function __construct($text_name, $text_value, $select_value_currency){
        $this->paymentApiName = "PayTrekApi";
        $this->$text_name = $text_name; 
        $this->$text_value = $text_value;
        $this->$select_value_currency = $select_value_currency;
    }
    public function checkApiStatus(){
    	$this->paymentApiName = "PayTrekApi";
    	$className = $this->paymentApiName;
    	$class = new $className;
    	return $class::apiStatus();
    }
}