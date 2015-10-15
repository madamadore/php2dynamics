<?php
require_once(dirname(__FILE__) . '/DynamicsIntegrator.class.php');
require_once(dirname(__FILE__) . '/ResponseEnvelope.class.php');

abstract class ReadOnlyEntity {
    
        protected $guid = "00000000-0000-0000-0000-000000000000";
        
        public function setGuid($guid) { $this->guid = $guid; }
        public function getGuid() { return $this->guid; }
        
        protected $statecode;
	protected $statuscode;
	
        public function setState($state) { $this->statecode = $state; }
        public function getState() { return $this->statecode; }
        
        public function setStatus($status) { $this->statuscode = $status; }
        public function getStatus() { return $this->statuscode; }
        
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
         * WARNING: Multiple keys are not implemented in this version.
         *
         * @return name of primary key field. 
         */
        public abstract function getPrimaryKey();
        
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
    
        protected function validateDate($date) {
            /*
            $mysql_dattime_regex = "/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$/";

            if ( ! preg_match($mysql_dattime_regex, $date)) {
                error_log("start_date in formato non valido. Deve essere yyyy-mm-ddThh:mm:ss");
                return false;
            } else {
                $dates = explode("T", trim( $date ));
                $date_part = $dates[0];
                $time_part = $dates[1];
            }*/

            $dates = explode("T", trim( $date ));
            $date_part = $dates[0];
            $time_part = $dates[1];
                
            return array( $date_part, $time_part );
        }
}