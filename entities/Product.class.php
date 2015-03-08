<?php

class Product extends ReadOnlyEntity {
    
    var $logicalName = "product";
    var $schema = array(
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
    
    public static function RetrieveMultiple($conditions = array(), $columns = "all") {
        return self::RetriveSingle( false, $conditions, $columns );
    }

    public static function Retrieve($guid) {
        return self::RetriveSingle( $guid );
    }
    
    protected static function RetriveSingle($guid = false, $conditions = array(), $columns = "all") {
        
        list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions, "productid" );
        $object = new Product();
        $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
        $entities = self::filterResponse($response, $object->schema);

        return $entities;
    }
}