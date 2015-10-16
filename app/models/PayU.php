<?php
class PayU extends Payment{ 
    function __construct($text_name, $text_value, $select_value_currency){
        $this->paymentApiName = "PayUApi";
        $this->$text_name = $text_name; 
        $this->$text_value = $text_value;
        $this->$select_value_currency = $select_value_currency;
    }   
}