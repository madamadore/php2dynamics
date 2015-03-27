<?php

class Appointment extends ReadOnlyEntity {
    
    var $logicalName = "appointment";
    var $schema = array(
        "subject" => "string",
        "description" => "string",
        "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
        "tb_language" => "option",
        "tb_maxpeople" => "int",
        "tb_participants" => "int",
        "serviceid" => array ( "type"=>"guid", "logicalName"=>"service" ),
        "location" => "string",
        "tb_tourprice" => "money",
        "tb_tourprice_base" => "money",
        "scheduledstart" => "datetime",
        "scheduledend" => "datetime",
        "category" => "string",
        "activityid" => "string",
        "tb_officeid" => array ( "type"=>"guid", "logicalName"=>"site" ),
        "tb_requiredtl" => "int",
        "tb_phototaken" => "option"
    );
    
    public static function getInstance() {
        return new Appointment();
    }
    
    public static function RetrieveMultiple($conditions = array(), $columns = "all") {
        return self::RetriveSingle( false, $conditions, $columns );
    }

    public static function Retrieve($guid) {
        return self::RetriveSingle( $guid );
    }
    
    protected static function RetriveSingle($guid = false, $conditions = array(), $columns = "all") {
        list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions, "activityid" );
        
        $object = self::getInstance();
        $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
        $entities = self::filterResponse($response, $object->schema);

        return $entities;
    }
}