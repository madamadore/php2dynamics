<?php

class Account extends Entity {

	var $logicalName = "account";
	var $schema = array("name" => "string" );
	var $validate = array( "required" => "name" );
	var $name;

	public function __construct($name) {
		$this->name = $name;
	}

        public function Create() {}
        public function Update() {}

        public static function RetriveMultiple($conditions = array(), $columns = "all") {}
        public static function Retrive($guid) {}
        public static function Delete($guid) {}
}
