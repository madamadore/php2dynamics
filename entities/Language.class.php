<?php

class Language extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "tb_language";
    }
    
    public function getSchema() {
        return array(
            "tb_languageid" => "string",
            "tb_name" => "string",
            "tb_maximum_group_size" => "int",
            "tb_languagecode" => "option",
            "tb_minimum_child_age" => "int",
            "tb_maximum_child_age" => "int",
            "tb_child_discount_percentage" => "float",
            "tb_unaccepted_children" => "option",
            "tb_minimum_unaccepted_child_age" => "int",
            "tb_maximum_unaccepted_child_age" => "int",
            "tb_default_lenght_unit" => "option",
            "tb_default_weight_unit" => "option"
        );
    }
    
    public function getPrimaryKey() {
        return "tb_languageid";
    }
}