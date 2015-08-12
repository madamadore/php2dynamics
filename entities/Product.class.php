<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Product extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "product";
    }
    
    public function getSchema() {
        return array(
            "description" => "string",
            "name" => "string",
            "price" => "money",
            "price_base" => "money",
            "productid" => "string",
            "tb_commercialname" => "string",
            "tb_dailyparticipants" => "int",
            "tb_included" => "string",
            "tb_notincluded" => "string",
            "tb_preparationtasks" => "string",
            "tb_rentalconditions" => "string",
            "tb_servicetype" => "option",
            "tb_sitesvisited" => "string",
            "tb_startingpoint" => "string",
            "tb_summary" => "string",
        );
    }
    
    public function getPrimaryKey() {
        return "productid";
    }

}