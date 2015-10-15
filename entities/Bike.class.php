<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Bike extends ReadOnlyEntity {
    
    public function getLogicalname() {
        return "tb_bike";
    }
    
    public function getSchema() {
        return array(
            "statecode" => "string",
            "statuscode" => "string",
            "tb_bikeid" => "string",
            "tb_comments" => "string",
            "tb_equipmentrecordid" => array ( "type"=>"guid", "logicalName"=>"equipment" ),
            "tb_framesize" => "int",
            "tb_gender" => "option",
            "tb_id" => "string",
            "tb_maxheight" => "float",
            "tb_minheight" => "float",
            "tb_modelid" => array ( "type"=>"guid", "logicalName"=>"bikemodel" ),
            "tb_name" => "string",
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_siteid" => array ( "type"=>"guid", "logicalName"=>"site" )
        );
    }
    
    public function getPrimaryKey() {
        return "tb_bikeid";
    }

}