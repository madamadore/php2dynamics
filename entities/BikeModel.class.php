<?php

class BikeModel extends ReadOnlyEntity {
    
    var $logicalName = "tb_bikemodel";
    var $schema = array(
        "tb_bikemodelid" => "string",
        "tb_brand" => "option",
        "tb_name" => "string",
        "tb_onsale" => "option",
        "tb_price" => "money",
        "tb_productgroupid" => array ( "type"=>"guid", "logicalName"=>"product" ),
        "tb_name" => "string",
        "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
        "tb_siteid" => array ( "type"=>"guid", "logicalName"=>"site" )
    );
    
    public static function RetrieveMultiple($conditions = array(), $columns = "all") {
        return self::RetriveSingle( false, $conditions, $columns );
    }

    public static function Retrieve($guid) {
        return self::RetriveSingle( $guid );
    }
    
    protected static function RetriveSingle($guid = false, $conditions = array(), $columns = "all") {
        
        list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions, "tb_bikemodelid" );
        
        $object = new BikeModel();
        $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
        $entities = self::filterResponse($response, $object->schema);

        return $entities;
    }
    
}