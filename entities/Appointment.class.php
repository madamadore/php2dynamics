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
            "category" => "string",
            "activityid" => "string",
            "tb_officeid" => array ( "type"=>"guid", "logicalName"=>"site" ),
            "tb_requiredtl" => "int",
            "tb_phototaken" => "option"
        );
    }
    
    public function getPrimaryKey() {
        return "activityid";
    }
    
    public static function getInstance() {
        return new Appointment();
    }
    
}