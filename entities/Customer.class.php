<?php
require_once dirname(__FILE__) . "/../php2dynamics/Entity.class.php";

class Customer extends Entity {
        
    public function getSchema() {
        return array(
                "fullname" => "string",
                "firstname" => "string",
                "lastname" => "string",
                "emailaddress1" => "string",
                "emailaddress2" => "string",
                "emailaddress3" => "string",
                "mobilephone" => "string",
                "description" => "string",
                "contactid" => "string",
                "statecode" => "string",
                "statuscode" => "string",
            );
    }
    
    public function getLogicalName() {
        return "contact";
    }
    
    public function getPrimaryKey() {
        return "contactid";
    }

    function __construct($fullname = "") {
        $this->fullname = $fullname;
    }
        
}
