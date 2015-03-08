<?php

abstract class ReadOnlyEntity {

    public static abstract function getInstance();
    
    public abstract static function RetrieveMultiple($conditions = array(), $columns = "all");
    public abstract static function Retrieve($guid);
    
    protected abstract static function RetriveSingle($guid = false, $conditions = array(), $columns = "all");
    
    protected static function instanceIntegrator($guid, $conditions) {
        $integrator = DynamicsIntegrator::getInstance();
            
        if ( $guid ) {
            $conditions = array(
                array( "attribute" => "activityid", "operator" => "Equal", "value" => $guid )
            );
        }
        
        return array( $integrator, $conditions );
    }
    
    protected static function filterResponse($response) {
        
        $xmlReader = new CrmXmlReader();
        $entities = $xmlReader->getEntities( $response );
        
        $objects = array();
        foreach ($entities as $entity) {
            $objects[] = $entity;
        }
        return $objects;
    }

}