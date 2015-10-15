<?php

class Data {
    
    var $servername = "local.test";
    var $database = "topbike";
    var $username = "topbike";
    var $password = "topbike";
    
    private function getConnection() {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        return $conn;
    }
    
    public function import(array $arguments) {
        $conn = $this->getConnection();
        foreach ($arguments as $arg) {
            switch ($arg) {
                case 'Appointment':
                    $now = new DateTime('NOW', new DateTimeZone('UTC'));
                    $end = (new DateTime('NOW', new DateTimeZone('UTC')))->add(new DateInterval('P200D'));
                    $conditions = array( 
                        array("attribute" => "scheduledstart", "operator" => "GreaterEqual", "value" => $now->format("c")),
                        array("attribute" => "scheduledend", "operator" => "LessEqual", "value" => $end->format("c"))
                    );
                    $ret = $this->importClass('Appointment', $conditions, $conn);
                    $className = 'Appointment';
                    break;
                case 'Contact':
                    $ret = $this->importClass('Customer', array(), $conn);
                    $className = 'Customer';
                    break;
                case 'Product':
                    $ret = $this->importClass('Tour', array(), $conn);
                    $className = 'Tour';
                    break;
                default:
                    $ret = $this->importClass($arg, array(), $conn);
                    $className = $arg;
                    break;
            }
            
            echo "Recuperati " . count($ret) . " " . $className ."\n";
            echo "Di seguito gli inserimenti falliti:\n";
            foreach ($ret as $sql) {
                # echo $sql."\n";
                $inserted = $conn->query($sql);
                if (!$inserted) {
                    echo $sql."\n";
                }
            }
            $conn->commit();
        }
        $conn->close();
    }
    
    public function getSingle($className, $guid) {
        $obj = new $className();
        $object = $obj->RetrieveSingle($guid);
        return $object;
    }
    
    public function importClass($className, $conditions = array(), $conn) {
        $obj = new $className();
        $collection = $obj->RetrieveMultiple($conditions);
        
        $ret = array();
        foreach ($collection as $single) {
            
            $schema = $obj->getSchema();
            $sql = "INSERT INTO ". $className ." (";
            $values = "";
            foreach ($schema as $key=>$type) {
                if (isset($single->$key)) {
                    $sql .= $key . ",";
                    if (!is_object($single->$key)) {
                        $values .= "'" . $conn->escape_string($single->$key) . "',";
                    } else {
                        $values .= "'" . $single->$key->id . "',";
                    }
                }
            }
            $sql = substr( $sql, 0, strlen($sql) - 1 );
            $values = substr( $values, 0, strlen($values) - 1);
            $sql .= ") VALUES (" . $values . ");";
            $ret[] = $sql;
        }
        return $ret;
    }
    
    
}
