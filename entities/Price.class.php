<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Price extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "tb_price";
    }
    
    public function getSchema() {
        return array(
            "tb_code" => "string",
            "tb_days" => "int",
            "tb_name" => "string",
            "tb_price" => "money",
            "tb_price_base" => "money",
            "tb_priceid" => "string",
            "tb_pricelistid" => array ( "type"=>"guid", "logicalName"=>"pricelevel" ),
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "statecode" => "string",
            "statuscode" => "string",
        );
    }
    
    public function getPrimaryKey() {
        return "tb_priceid";
    }

}