<?php

class Price extends ReadOnlyEntity {
    
    var $logicalName = "tb_price";
    var $schema = array(
        "tb_code" => "string",
        "tb_days" => "int",
        "tb_name" => "string",
        "tb_price" => "money",
        "tb_price_base" => "money",
        "tb_priceid" => "string",
        "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
    );
    
    public static function RetrieveMultiple($conditions = array(), $columns = "all") {
        return self::RetriveSingle( false, $conditions, $columns );
    }

    public static function Retrieve($guid) {
        return self::RetriveSingle( $guid );
    }
    
    protected static function RetriveSingle($guid = false, $conditions = array(), $columns = "all") {
        
        list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions, "tb_priceid" );
        $object = new Price();
        $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
        $entities = self::filterResponse( $response, $object->schema );

        return $entities;
    }
}