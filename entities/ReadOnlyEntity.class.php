<?php
include_once (dirname(__FILE__) . '/../dynamics/DynamicsIntegrator.class.php');

abstract class ReadOnlyEntity {
    
    public abstract static function RetrieveMultiple($conditions = array(), $columns = "all");
    public abstract static function Retrieve($guid);
    
    protected abstract static function RetriveSingle($guid = false, $conditions = array(), $columns = "all");
    
    protected static function instanceIntegrator($guid, $conditions, $primaryKeyName) {
        $integrator = DynamicsIntegrator::getInstance();
            
        if ( $guid ) {
            $conditions = array(
                array( "attribute" => $primaryKeyName, "operator" => "Equal", "value" => $guid )
            );
        }
        
        return array( $integrator, $conditions );
    }
    
    protected static function filterResponse($response, $schema = array()) {
        
        $xmlReader = new CrmXmlReader(false);
        $entities = $xmlReader->getEntities( $response, $schema );
        
        $objects = array();
        foreach ($entities as $entity) {
            $objects[] = $entity;
        }
        return $objects;
    }

}