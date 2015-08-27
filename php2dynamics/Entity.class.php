<?php
require_once(dirname(__FILE__) . '/ReadOnlyEntity.class.php');

abstract class Entity extends ReadOnlyEntity {
        
        public static $_STATE = array( "Open" => "0", "Closed" => "1", "Canceled" => "2", "Scheduled" => "3" );
	public static $_STATUS = array( "Tentative" => "2", "Awaiting Deposit" => "1", "Completed" => "8",
	                                "Canceled" => "9", "Confirmed" => "4", "In Progress" => "6", "No Show" => "10" );
        
        protected function UpdateState() {
            $integrator = DynamicsIntegrator::getInstance();
            
            $response = $integrator->doStateRequest( ReadOnlyEntity::$_STATE[$this->state], 
                                                     ReadOnlyEntity::$_STATUS[$this->status],
                                                    $this->getGuid(), $this->getLogicalName() );
            $r = new ResponseEnvelope($response);
        
            if ( $r->isSuccess() ) {
                return true;
            }
            
            return $r->getErrorMessage();
        }
        
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
        
}
