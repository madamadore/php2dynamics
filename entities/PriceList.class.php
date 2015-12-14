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
            "tb_languages" => "string",
            "tb_season" => "option",
            "pricelevelid" => "string",
            "statecode" => "string",
            "statuscode" => "string",
        );
    }
    
    public function getPrimaryKey() {
        return "pricelevelid";
    }

}