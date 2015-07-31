<?php

include_once (dirname(__FILE__) . '/ReadOnlyEntity.class.php');

abstract class Entity extends ReadOnlyEntity {

	var $guid = "00000000-0000-0000-0000-000000000000";
	var $logicalName;
	var $state;
	var $status;
	var $schema;

        public function Create() {
            $integrator = DynamicsIntegrator::getInstance();
            $response = $integrator->doRequest( $this, "Create" );
            return $response;
        }
        
        public function Update($guid) {
            $integrator = DynamicsIntegrator::getInstance();
            $response = $integrator->doRequest( $this, "Update", $guid );
            return $response;
        }

        public static function Delete($guid) {
            $integrator = DynamicsIntegrator::getInstance();
            $response = $integrator->doRequest( $this, "Delete", $guid );
            return $response;
        }

}
