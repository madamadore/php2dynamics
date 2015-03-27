<?php

abstract class Entity {

    var $state;     // An MS CRM Entity always have a state
    var $status;    // An MS CRM Entity always have a status
    var $guid = "00000000-0000-0000-0000-000000000000";
    var $logicalName;
    var $primaryKey;
    var $schema;

    public function __construct() {
        foreach ( $this->schema as $key=>$typeOrArray ) {

            if ( is_array( $typeOrArray ) && isset( $typeOrArray[ "primaryKey" ] ) ) {
                $this->primaryKey = $key;
                $break;
            }

            // TODO: Implement check who search for primaryKey

        }
    }

    public function Create() {}
    public function Update() {
        $dynamics = DynamicsIntegrator::getInstance();
        $guid = $this->{ $this->primaryKey };
        $response = $dynamic->doRequest( $this, "Update", $guid );
    }
    public static function Delete($guid) {}
    public static function Retrieve($guid, $conditions, $columns) {
        $conditions[];
    }
    public static function RetrieveMultiple($conditions, $columns) {}

    private function isPrimaryKey($typeOrArray) {

    }
}
