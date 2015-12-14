<?php

class Database {
    
    public static $database_name = "topbike";
    
    public static $_TABLES = array('Appointment', 'BikeModel', 'Booking', 'Contact', 'Product', 'Equipment', 'PriceList', 'PriceListItem');
    
    public function create(array $tables) {
        
        $outputFile = fopen("output.sql", "w");
        foreach ($tables as $className) {
            switch ($className) {
                case 'Contact':
                    $entity = new Customer();
                    break;
                case 'Product':
                    $entity = new Product();
                    break;
                default:
                    $entity = new $className();
            }
            $sqlCreate = $this->getSqlCreate($entity);
            foreach($sqlCreate as $sql) {
                fwrite($outputFile, $sql);
            }
        }
        fclose($outputFile);
    }
    
    private function getSqlCreate(ReadOnlyEntity $entity) {
        $schema = $entity->getSchema();
        $primaryKey = $entity->getPrimaryKey();
        
        if ( !array_key_exists($primaryKey, $schema) ) {
            $schema[$primaryKey] = "string";
        }
        $tableName = get_class($entity);
        $retval = array();
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $tableName . " (\n";
        foreach ($schema as $name => $type) {
            $columnType = is_array($type) ? $type["type"] : $type;
            if ($columnType == "guid_array") {
                $logicalName = $type["logicalName"];        
                $retval[] = $this->getOneToManyTable($tableName, $logicalName);
            } else {
                $default = $this->getDefault($name, $primaryKey);
                $sql .= "\t" . $this->getSingleColumnCreate($name, $columnType, $default) . "\n";
            }
        }
        $sql .= "\tPRIMARY KEY(`" . $primaryKey . "`)\n";
        $sql .= ") ENGINE=InnoDB;\n";
        $retval[] = $sql;
        
        return $retval;
    }
    
    private function getOneToManyTable($tableName, $logicalName) {        
        $sql = "CREATE TABLE IF NOT EXISTS " . $tableName . "_" . strtoupper(substr($logicalName, 0, 1)) . substr($logicalName, 1) . " (\n";
        $sql .= "\t". strtolower($tableName) ."_id VARCHAR(255) NOT NULL,\n";
        $sql .= "\t".$logicalName ."_id VARCHAR(255) NOT NULL,\n";
        $sql .= "\tPRIMARY KEY(".strtolower($tableName) ."_id, ".$logicalName ."_id)\n";
        $sql .= ") ENGINE=InnoDB;\n";
        return $sql;
    }
    
    private function getDefault($name, $primaryKey) {
        $default = "DEFAULT NULL";
        if ($primaryKey == $name) { 
            $default = "NOT NULL"; 
        }
        return $default;
    }
    
    private function getSingleColumnCreate($name, $type, $default = "DEFAULT NULL") {
        $sqlType = null;
        switch ($type) {
            case "boolean":
                $sqlType = "TINYINT(1)";
                break;
            case "datetime":
                $sqlType = "TIMESTAMP";
                $default = "NOT NULL";
                break;
            case "int":
                $sqlType = "INT(11)";
                break;
            case "float":
            case "money":
                $sqlType = "DECIMAL(10,2)";
                break;
            case "guid":
            case "option":
            case "string":
                $sqlType = "VARCHAR(255)";
                break;
        }
        return "`" . $name . "` " . $sqlType . " " . $default . ", ";
    }
}