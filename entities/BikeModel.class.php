<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class BikeModel extends ReadOnlyEntity {
    
    public function getLogicalName() { return "tb_bikemodel"; }
    
    public function getSchema() {
        return array(
            "statecode" => "string",
            "statuscode" => "string",
            "tb_bikemodelid" => "string",
            "tb_brand" => "option",
            "tb_name" => "string",
            "tb_onsale" => "option",
            "tb_price" => "money",
            "tb_productgroupid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_siteid" => array ( "type"=>"guid", "logicalName"=>"site" ),
            "tb_childseat_availability" => "option",
            "tb_rental_availability" => "option"
        );
    }
    
    public function getPrimaryKey() {
        return "tb_bikemodelid";
    }
    
}