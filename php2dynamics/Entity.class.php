<?php

abstract class Entity {
        
	protected $guid = "00000000-0000-0000-0000-000000000000";
        protected $state;
	protected $status;
	
        public function setGuid($guid) { $this->guid = $guid; }
        public function getGuid() { return $this->guid; }
        
        public function setState($state) { $this->state = $state; }
        public function getState() { return $this->state; }
        
        public function setStatus($status) { $this->status = $status; }
        public function getStatus() { return $this->status; }
        /**
         * WARNING: Multiple keys are not implemented in this version.
         *
         * @return name of primary key field. 
         */
        public abstract function getPrimaryKey();
        
        protected function UpdateState() {
            $integrator = DynamicsIntegrator::getInstance();
            
            $response = $integrator->doStateRequest( $this->getState(), $this->getStatus(),
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
        
        /**
         * @return schema of fileds as array 
         */
        public abstract function getSchema();
        
        /**
         * @return logical name of this entity
         */
        public abstract function getLogicalName();
        
        /**
         * Retrieve multiple instances of current Entity.
         * @return array of entities or string error.
         */
        public function RetrieveMultiple($conditions = array(), $columns = "all") {
            $classname = get_class($this);
            $obj = new $classname();
            return $obj->Retrieve( false, $conditions, $columns );
        }

        /**
         * Retrieve single instance of current Entity.
         * @return single entity, null if nothing has been found or error string.
         */
        public function RetrieveSingle($guid) {
            $classname = get_class($this);
            $obj = new $classname();
            
            $conditions = array(
                array( "attribute" => $obj->getPrimaryKey(), "operator" => "Equal", "value" => $guid )
            );
            $objects = $obj->Retrieve( $guid, $conditions );
            
            if ( ! is_array( $objects ) ) {
                return $objects;
            } else if ( count( $objects ) == 0 ) {
                return null;
            }
            return $objects[0];
        }

        protected function Retrieve($guid = false, $conditions = array(), $columns = "all") {
            $integrator = DynamicsIntegrator::getInstance();
            
            $classname = get_class($this);
            $object = new $classname();
            
            $response = $integrator->doRequest( $object, "RetrieveMultiple", $guid, $conditions, $columns );
            $r = new ResponseEnvelope($response);
        
            if ( $r->isSuccess() ) {
                $entities = $object->filterResponse( $response, $object->getSchema() );
                return $entities;
            }
            return $r->getErrorMessage();
        }

        protected function filterResponse($response, $schema = array()) {

            $xmlReader = new CrmXmlReader( false );
            $entities = $xmlReader->getEntities( $response, $schema );
            
            $objects = array();
            foreach ($entities as $entity) {
                $objects[] = $entity;
            }
            
            return $objects;
        }

}
