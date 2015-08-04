<?php
require_once dirname(__FILE__) . "/../dynamics/Entity.class.php";

class Account extends Entity {

	var $logicalName = "account";
	var $schema = array(
		"fullname" => "string",
	);

	function __construct($fullname) {
            $this->fullname = $fullname;
	}
        
        public static function getPrimaryKey() {
            return "accountid";
        }
}