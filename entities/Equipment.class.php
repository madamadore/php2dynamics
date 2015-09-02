<?php
require_once dirname(__FILE__) . "/../php2dynamics/ReadOnlyEntity.class.php";

class Equipment extends ReadOnlyEntity {
    
    public function getLogicalName() {
        return "equipment";
    }
    
    public function getSchema() {
        return array(
            "tb_id" => "string",
            "description" => "string",
            "equipmentid" => "string",
            "name" => "string",
            "siteid" => array ( "type"=>"guid", "logicalName"=>"site" ),
            "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
            "tb_type" => "option",
            "tb_primarylanguage" => "option",
        );
    }
    
    public function getPrimaryKey() {
        return "equipmentid";
    }
    
    public function checkAvaibility($resources, $start_date, $end_date, $gap = "1 hour") {
        $integrator = DynamicsIntegrator::getInstance();

        list($start_date, $start_time) = $this->validatedate( $start_date );
        list($end_date, $end_time) = $this->validatedate( $end_date );
        
        $response = $integrator->doAvaibilityRequest($resources, $start_date, $start_time, $end_date, $end_time, $gap);

        $accountsArray = array();
        if($response!=null && $response!="") {

            $responsedom = new DomDocument();
            $responsedom->loadXML($response);
            $entities = $responsedom->getElementsbyTagName("ArrayOfTimeInfo");
            $counter = 0;
            foreach($entities as $entity) {
                $reservation = array();
                $available = true;
                $kvptypes = $entity->getElementsbyTagName("TimeInfo");
                foreach($kvptypes as $kvp) {
                    $start =  $kvp->getElementsbyTagName("Start")->item(0)->textContent;
                    $end =  $kvp->getElementsbyTagName("End")->item(0)->textContent;
                    $timecode =  $kvp->getElementsbyTagName("TimeCode")->item(0)->textContent;
                    $effort =  $kvp->getElementsbyTagName("Effort")->item(0)->textContent;

                    if ($timecode != "Available") {
                        $available = false;
                    }

                    $reservation[] = array ( "status" => $timecode,
                                             "start" => $start,
                                             "end" => $end,
                                             "effort" => $effort );
                }
                $guid = $resources[ $counter ];

                $avaibilityReponse = array( "avaibility" => $available, "details" => $reservation);
                $accountsArray[ $guid ] = $avaibilityReponse;
                $counter++;
            }
        } else {
            return false;
        }

        return $accountsArray;
    }
}