<?php

class Discount {
    
    public function getLogicalName() {
        return "tb_discount";
    }
    
    public function getSchema() {
        return array(
            "tb_code" => "string",
            "tb_discount" => "int", // percentuale di sconto
            "tb_regarding" => "string",
            "tb_name" => "string",
            "tb_discount_amount" => "money",
            "tb_discount_type" => "option",
            "tb_discountid" => "string"
        );
    }
    
    public function getPrimaryKey() {
        return "tb_discountid";
    }
}