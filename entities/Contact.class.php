<?php
require_once dirname(__FILE__) . "/../dynamics/Entity.class.php";

class Contact extends Entity {
        
    public function getSchema() {
        return array(
                    "fullname" => "string",
                    "firstname" => "string",
                    "lastname" => "string",
                    "emailaddress1" => "string",
                    "emailaddress2" => "string",
                    "emailaddress3" => "string",
                    "mobilephone" => "string",
                    "description" => "string"
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
