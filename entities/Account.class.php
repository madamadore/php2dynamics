<?php

class Account extends Entity {

	var $logicalName = "account";
	var $schema = array("name" => "string" );
	var $validate = array( "required" => "name" );
	var $name;

	public function __construct($name) {
		$this->name = $name;
	}

}
