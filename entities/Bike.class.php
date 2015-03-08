<?php

class Bike extends ReadOnlyEntity {
    
    var $logicalName = "tb_bike";
    var $schema = array(
        "tb_bikeid" => "string",
        "tb_comments" => "string",
        "tb_equipmentrecordid" => array ( "type"=>"guid", "logicalName"=>"equipment" ),
        "tb_framesize" => "int",
        "tb_gender" => "option",
        "tb_id" => "string",
        "tb_maxheight" => "double",
        "tb_minheight" => "double",
        "tb_modelid" => array ( "type"=>"guid", "logicalName"=>"bikemodel" ),
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
        
        list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions, "tb_bikeid" );
        
        $object = new Bike();
        $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
        $entities = self::filterResponse($response, $object->schema);

        return $entities;
    }
}