<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class PriceListItem extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "productpricelevel";
    }
    
    public function getSchema() {
        return array(
            "amount" => "money",
            "amount_base" => "money",
            "percentage" => "float",
            "pricelevelid" => array ( "type"=>"guid", "logicalName"=>"pricelevel" ),
            "productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "productpricelevelid" => "string",
            "uomid" => array ( "type"=>"guid", "logicalName"=>"uom" ),
        );
    }
    
    public function getPrimaryKey() {
        return "productpricelevelid";
    }

}