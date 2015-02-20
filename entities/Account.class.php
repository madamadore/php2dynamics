<?php

class Account extends Entity {

	public $logicalName = "account";
	public $schema = array(
						"name"=>array("string")
						);
	public $name;

	function __construct($name) {
		$this->name = $name;
	}

}
