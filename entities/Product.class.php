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
            "statecode" => "string",
            "statuscode" => "string",
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
            "tb_available_languages" => "string",
            "tb_bikeprofile" => "option",
            "tb_childseat_availability" => "option",    // nuovo
            "tb_bike_product_category" => "option",     // nuovo
            "tb_display_on_website" => "boolean",       // nuovo
            "tb_product_type" => "option",
            "tb_available_languages" => "string",
            "tb_guide_occupation_factor" => "option"
        );
    }

    public function getPrimaryKey() {
        return "productid";
    }

}