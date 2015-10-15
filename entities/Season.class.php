<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Season extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "tb_season";
    }
    
    public function getSchema() {
        return array(
            "tb_name" => "string",
            "tb_start_date" => "datetime",
            "tb_end_date" => "datetime",
            "tb_season_type" => "option",
            "statecode" => "string",
            "statuscode" => "string",
            "tb_seasonid"=> "string"
        );
    }
    
    public function getPrimaryKey() {
        return "tb_seasonid";
    }

}