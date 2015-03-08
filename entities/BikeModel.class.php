<?php

class BikeModel extends ReadOnlyEntity {
    
    public static function getInstance() {
        return new BikeModel();
    }
    
    public static function RetrieveMultiple($conditions = array(), $columns = "all") {
        return self::RetriveSingle( false, $conditions, $columns );
    }

    public static function Retrieve($guid) {
        return self::RetriveSingle( $guid );
    }
    
    protected static function RetriveSingle($guid = false, $conditions = array(), $columns = "all") {
        list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions );
        
        $object = self::getInstance();
        $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
        $entities = self::filterResponse($response);

        return $entities;
    }
    
}