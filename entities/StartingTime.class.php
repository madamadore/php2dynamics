<?php

class StartingTime extends ReadOnlyEntity {
    
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
            "tb_starting_timeid" => "string",
            "tb_part_of_the_day" => "option"
        );
    }
    
    public function getPrimaryKey() {
        return "tb_starting_timeid";
    }
}