<?php
abstract class Payment{
    public $text_name, $text_value, $select_value_currency;
    abstract public function checkApiStatus();
    abstract public function checkCurrency();
    abstract public function pay();
    abstract public function sendVoucher();
}