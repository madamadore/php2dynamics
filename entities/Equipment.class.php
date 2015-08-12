<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Equipment extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "equipment";
    }
    
    public function getSchema() {
        return array(
            "tb_id" => "string",
            "description" => "string",
            "equipmentid" => "string",
            "name" => "string",
            "siteid" => array ( "type"=>"guid", "logicalName"=>"site" ),
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_type" => "option",
            "tb_primarylanguage" => "option",
        );
    }
    
    public function getPrimaryKey() {
        return "equipmentid";
    }
}