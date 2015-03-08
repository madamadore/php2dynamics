<?php

class CrmXmlReader {
    
    const FULL_MODE = true;
    const SCHEMA_MODE = false;
    
    private $mode = self::FULL_MODE;
    
    public function __construct($mode = self::FULL_MODE) {
        $this->mode = $mode;
    }
    
    public function getEntities($xml, $schema = array()) {
        $responsedom = new DomDocument();
        $responsedom->loadXML( $xml );

        $arrayOfObjects = array();
        $entities = $responsedom->getElementsbyTagName( "Entity" );
        foreach ( $entities as $entity ) {
            
            $object = $this->getEntity( $entity, $schema, false );
            $arrayOfObjects[] = $object;
            
        }

        return $arrayOfObjects;
    }
    
    private function getEntity($xml, $schema, $ignore_schema = false) {
        $object = new stdClass();
        $nodes = $xml->getElementsbyTagName( "KeyValuePairOfstringanyType" );

        foreach( $nodes as $node ) {
            
            $key = $node->getElementsbyTagName( "key" )->item(0)->textContent;

            if ( $this->mode == self::FULL_MODE || $ignore_schema || array_key_exists($key, $schema) ) {
                
                $value = $this->getValue( $node, $schema );
                $object->{$key} = $value;
                
            }
        }
        
        return $object;
    }
    
    private function getValue($xml, $schema) {
        
        $strfulltype = $xml->getElementsbyTagName( "value" )->item(0)->attributes->item(0)->value;
        $temp = explode( ":", $strfulltype );
        $type = $temp[1];
        
        switch ( $type ) {
            case "EntityCollection":
                $value = $this->getEntityCollection($xml, $schema);
                break;
            case "EntityReference":
                $value = $this->getEntityReference($xml);
                break;
            default:
                $value = $xml->getElementsbyTagName( "value" )->item(0)->textContent;
        }
        
        return $value;
    }
    
    private function getEntityCollection($xml, $schema) {
        
        if ( $this->mode == self::SCHEMA_MODE ) {
            $logicalName = $this->getLogicalName( $xml, $schema );
        }

        $entities =  $xml->getElementsbyTagName( "Entity" );
        $value = array();
        foreach ( $entities as $single ) {

            $entity = $this->getEntity( $single, $schema, true );

            if ($this->mode == self::SCHEMA_MODE ) {
                $entity = $this->filterEntityCollection( $logicalName, $entity );
            }

            if ( $entity ) {
                $value[] = $entity;
            }

        }
        
        return $value;
    }
    
    private function getEntityReference($xml) {
        
        $id =  $xml->getElementsbyTagName( "Id" )->item(0)->textContent;
        $logicalName =  $xml->getElementsbyTagName( "LogicalName" )->item(0)->textContent;
        $name =  $xml->getElementsbyTagName( "Name" )->item(0)->textContent;

        $value = new stdClass();
        $value->id = $id;
        $value->logicalName = $logicalName;
        $value->name = $name;
        
        return $value;
    }
    
    private function getLogicalName($xml, $schema) {
        
        $key = $xml->getElementsbyTagName( "key" )->item(0)->textContent;
        $logicalName = false;
        if ( array_key_exists( "logicalName", $schema[ $key ] ) ) {
            $logicalName = $schema[ $key ][ "logicalName" ];
        }

        if ( array_key_exists( "defaultLogicalName", $schema[ $key ] ) ) {
            $logicalName = $schema[ $key ][ "defaultLogicalName" ];
        }
        
        return $logicalName;
    }
    
    private function filterEntityCollection($logicalName, $entity) {
        foreach ($entity as $key=>$value) {
            if ( is_object( $value ) && $logicalName == $value->logicalName ) {
                return $value;
            }
        }
        return false;
    }
}