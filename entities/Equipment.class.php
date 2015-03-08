<?php

class Equipment extends ReadOnlyEntity {
    
    var $logicalName = "equipment";
    var $schema = array(
        "tb_id" => "string",
        "description" => "string",
        "equipmentid" => "string",
        "name" => "string",
        "siteid" => array ( "type"=>"guid", "logicalName"=>"site" ),
        "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
        "tb_type" => "option",
        "tb_primarylanguage" => "option",
    );
    
    public static function RetrieveMultiple($conditions = array(), $columns = "all") {
        return self::RetriveSingle( false, $conditions, $columns );
    }

    public static function Retrieve($guid) {
        return self::RetriveSingle( $guid );
    }
    
    protected static function RetriveSingle($guid = false, $conditions = array(), $columns = "all") {
        list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions, "equipmentid" );
        
        $object = new Equipment();
        $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
        $entities = self::filterResponse( $response, $object->schema );

        return $entities;
    }
}