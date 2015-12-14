<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Unit extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "uom";
    }
    
    public function getSchema() {
        return array(
            "name" => "string",
            "quantity" => "float",
            "uomscheduleid" => "string",
            "uomid" => "string",
        );
    }
    
    public function getPrimaryKey() {
        return "uomid";
    }

}