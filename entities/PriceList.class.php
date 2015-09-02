<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class PriceList extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "pricelevel";
    }
    
    public function getSchema() {
        return array(
            "name" => "string",
            "description" => "string",
            "begindate" => "datetime",
            "enddate" => "datetime",
        );
    }
    
    public function getPrimaryKey() {
        return "pricelevelid";
    }

}