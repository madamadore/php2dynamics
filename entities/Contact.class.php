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

}
