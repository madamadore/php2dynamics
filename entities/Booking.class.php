<?php

class Booking extends Entity {

	var $logicalName = "serviceappointment";
	var $avaibility_offline = false;
	var $integration_status = false;
	var $schema = array(
                        "description" => "string",
                        "duration" => "int",
                        "regardingobjectid" => array ( "type"=>"guid", "logicalName"=>"contact" ),
                        "resources" => array ( "type"=>"guid_array", "defaultLogicalName"=>"equipment" ),
                        "scheduledstart" => "datetime",
                        "scheduledend" => "datetime",
                        "serviceid" => array ( "type"=>"guid", "logicalName"=>"service" ),
                        "siteid" => array ( "type"=>"guid", "logicalName"=>"site" ),
                        "state" => "option",
                        "status" => "option",
                        "subject" => "string",
                        "tb_bikeids" => "string",
                        "tb_bookingdate" => "datetime",
                        "tb_bookingcode" => "string",
                        "tb_bookingtype" => "option",
                        "tb_contact_gender" => "option",
                        "tb_deposit" => "money",
                        "tb_language" => "option",
                        "tb_materialdetails" => "string",
                        "tb_openamount" => "money",
                        "tb_participants" => "int",
                        "tb_productid" => array ( "type"=>"guid", "logicalName"=>"product" ),
                        "tb_scheduledbikes" => "float",
                        "tb_servicetype" => "option",
                        "tb_tourid" => array ( "type"=>"guid", "logicalName"=>"appointment" ),
                        "tb_totalamount" => "money",
                        "tb_topbikerevenue" => "money",
                        "tb_tourprice" => "money"
                    );

	public function __construct($subject) {
            $this->subject = $subject;
	}
        
        public function Create() {}
        public function Update() {}

        public static function RetriveMultiple($conditions = array(), $columns = "all") {
            return self::RetriveBooking( false, $conditions, $columns );
        }
        
        public static function Retrive($guid) {
            return self::RetriveBooking( $guid );
        }
        
        private static function RetriveBooking($guid = false, $conditions = array(), $columns = "all") {
            
            $integrator = DynamicsIntegrator::getInstance();
            
            if ( $guid ) {
                $conditions = array(
                    array( "attribute" => "activityid", "operator" => "Equal", "value" => $guid )
                );
            }
            $booking = new Booking( "emptyobject" );
            $response = $integrator->doRequest( $booking, "RetrieveMultiple", $guid, $conditions, $columns );
                
            $responsedom = new DomDocument();
            $responsedom->loadXML( $response );
            
            $arrayOfObjects = array();
            $entities = $responsedom->getElementsbyTagName( "Entity" );
            foreach ( $entities as $entity ) {
                
                $object = new stdClass();
                $nodes = $entity->getElementsbyTagName( "KeyValuePairOfstringanyType" );
                
                foreach( $nodes as $node ) {
                    $key =  $node->getElementsbyTagName( "key" )->item(0)->textContent;
                    $value =  $node->getElementsbyTagName( "value" )->item(0)->textContent;
                    $type = $node->getElementsbyTagName( "value" )->item(0)->getAttribute( 'type' );
                    
                    $object->{$key} = $value;
                }
                
                $arrayOfObjects[] = $object;
            }
            
            return $arrayOfObjects;
        }
        
        public static function Delete($guid) {}
}
