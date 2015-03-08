<?php

class CrmXmlReader {
    
    public function getEntities($xml) {
        $responsedom = new DomDocument();
        $responsedom->loadXML( $xml );

        $arrayOfObjects = array();
        $entities = $responsedom->getElementsbyTagName( "Entity" );
        foreach ( $entities as $entity ) {
            
            $object = $this->getEntity( $entity );
            $arrayOfObjects[] = $object;
            
        }

        return $arrayOfObjects;
    }
    
    private function getEntity($xml) {
        $object = new stdClass();
        $nodes = $xml->getElementsbyTagName( "KeyValuePairOfstringanyType" );

        foreach( $nodes as $node ) {
            
            $key =  $node->getElementsbyTagName( "key" )->item(0)->textContent;
            $value = $this->getValue( $node );

            $object->{$key} = $value;
        }
        
        return $object;
    }
    
    private function getValue($xml) {
        
        $strfulltype = $xml->getElementsbyTagName( "value" )->item(0)->attributes->item(0)->value;
        $temp = explode( ":", $strfulltype );
        $type = $temp[1];
        
        switch ( $type ) {
            case "EntityCollection":
                $entities =  $xml->getElementsbyTagName( "Entity" );
                $value = array();
                foreach ( $entities as $entity ) {
                    $value[] = $this->getEntity( $entity );
                }
                break;
            case "EntityReference":
                $value = $this->getEntityReference($xml);
                break;
            default:
                $value =  $xml->getElementsbyTagName( "value" )->item(0)->textContent;
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
}