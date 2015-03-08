<?php

class Contact extends Entity {

	var $logicalName = "contact";

	var $schema = array(
		"fullname" => "string",
		"firstname" => "string",
		"lastname" => "string",
		"emailaddress1" => "string",
		"emailaddress2" => "string",
		"emailaddress3" => "string",
		"mobilephone" => "string",
		"description" => "string"
	);

	var $validate = array( "required" => "fullname",
							"email" => "emailaddress",
							"phonenumber" => "mobilephone" );

	function __construct($fullname) {
		$this->fullname = $fullname;
	}

        public function Create() {}
        public function Update() {}
        
        public static function RetrieveMultiple($conditions = array(), $columns = "all") {
            return self::RetriveSingle( false, $conditions, $columns );
        }

        public static function Retrieve($guid) {
            return self::RetriveSingle( $guid );
        }

        protected static function RetriveSingle($guid = false, $conditions = array(), $columns = "all") {
            
            list( $integrator, $conditions ) = self::instanceIntegrator( $guid, $conditions, "contactid" );

            $object = new Contact( "emptyobject" );
            $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
            $entities = self::filterResponse($response, $object->schema);

            return $entities;
        }

        public static function Delete($guid) {}
        
}
