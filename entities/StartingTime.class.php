<?php

class StartingTime {
    
    public function getLogicalName() {
        return "tb_starting_time";
    }
    
    public function getSchema() {
        return array(
            "statecode" => "string",
            "statuscode" => "string",
            "tb_season" => "option",
            "tb_duration" => "int",
            "tb_starting_time" => "option",
            "tb_starting_timeid" => "string"
        );
    }
    
    public function getPrimaryKey() {
        return "tb_starting_timeid";
    }
}