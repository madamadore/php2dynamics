<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Appointment extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "appointment";
    }
    
    public function getSchema() {
        return array(
            "subject" => "string",
            "description" => "string",
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_language" => "option",
            "tb_maxpeople" => "int",
            "tb_participants" => "int",
            "serviceid" => array ( "type"=>"guid", "logicalName"=>"service" ),
            "location" => "string",
            "tb_tourprice" => "money",
            "tb_tourprice_base" => "money",
            "scheduledstart" => "datetime",
            "scheduledend" => "datetime",
            "statecode" => "string",
            "statuscode" => "string",
            "category" => "string",
            "activityid" => "string",
            "tb_officeid" => array ( "type"=>"guid", "logicalName"=>"site" ),
            "tb_requiredtl" => "int",
            "tb_phototaken" => "option",
            "tb_ask_ebike" => "option",
            "tb_ebike_default_value" => "option",
            "tb_ebike_fee" => "money",
            "tb_child_discount" => "float"
        );
    }
    
    public function getPrimaryKey() {
        return "activityid";
    }
    
    public static function getInstance() {
        return new Appointment();
    }
    
}