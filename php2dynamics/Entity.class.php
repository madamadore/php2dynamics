<?php
require_once(dirname(__FILE__) . '/ReadOnlyEntity.class.php');

abstract class Entity extends ReadOnlyEntity {
        
        /**
         * Insert current entity.
         * @return generated guid or string error.
         */
        public function Create() {
            $integrator = DynamicsIntegrator::getInstance();
            $response = $integrator->doRequest( $this, "Create" );
            $r = new ResponseEnvelope($response);
            if ($r->isSuccess()) {
                $guid = $r->getGeneratedId();
                $this->setGuid( $guid );
                return $guid;
            }
            return $r->getErrorMessage();
        }
        
        /**
         * Update current entity.
         * @return true or string error.
         */
        public function Update() {
            $integrator = DynamicsIntegrator::getInstance();
            $response = $integrator->doRequest( $this, "Update", $this->getGuid() );
            $r = new ResponseEnvelope($response);
            if ($r->isSuccess()) {
                return true;
            }
            return $r->getErrorMessage();
        }
        
        /**
         * Delete an entity.
         * @param string $guid id of entity to delete.
         * @return true or string error.
         */
        public function Delete() {
            $integrator = DynamicsIntegrator::getInstance();
            $response = $integrator->doRequest( $this, "Delete", $this->getGuid() );
            $r = new ResponseEnvelope($response);
            if ($r->isSuccess()) {
                return true;
            }
            return $r->getErrorMessage();
        }
        
        public function __set($name, $value) {
            $this->$name = $value;
        }

}
