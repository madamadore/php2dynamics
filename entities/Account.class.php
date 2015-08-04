<?php
require_once dirname(__FILE__) . "/../dynamics/Entity.class.php";

class Account extends Entity {

    public function getSchema() {
        return array(
                "name" => "string",
                "description" => "string"
            );
    }
    
    public function getLogicalName() {
        return "account";
    }
    
    public function getPrimaryKey() {
        return "accountid";
    }

}