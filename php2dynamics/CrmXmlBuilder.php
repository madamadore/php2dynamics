<?php

class CrmXmlBuilder {

	private $organizationServiceURL;
	private $securityData;

	public function __construct($organizationServiceURL, $securityData) {
		$this->organizationServiceURL = $organizationServiceURL;
		$this->securityData = $securityData;
	}

	private function do_entity_tag($entity, $logicalName, $schema) {
		$xml = '<b:Entity>'
                        . '<b:Attributes>';
		$xml .= $this->fetchEntityFields( $entity, $schema );
		$xml .= '</b:Attributes>
                        <b:EntityState i:nil="true" />
                        <b:FormattedValues />
                        <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
                        <b:LogicalName>' . $logicalName . '</b:LogicalName>
                        <b:RelatedEntities />
                        </b:Entity>';
                return $xml;
	}

	private function do_entity_value($key, $entity) {

		$xml = '<b:KeyValuePairOfstringanyType>
                        <c:key>' . $key . '</c:key>
					<c:value i:type="b:Entity">
						<b:Attributes>';
		$xml .= $this->fetchEntityFields( $entity, $entity->getSchema() );
		$xml .= '</b:Attributes>
                        <b:EntityState i:nil="true" />
	                <b:FormattedValues />
	                <b:Id>' . $entity->getGuid() . '</b:Id>
                        <b:LogicalName>' . $entity->getLogicalName() . '</b:LogicalName>
	                <b:RelatedEntities />
			</c:value>
                        </b:KeyValuePairOfstringanyType>';
                return $xml;
	}

        
	private function do_array_entities_value($key, $arrayOfEntities, $defaultLogicalName) {
                
		$xml = '<b:KeyValuePairOfstringanyType>
                            <c:key>'.$key.'</c:key>
                            <c:value i:type="b:ArrayOfEntity">';

                
		foreach ($arrayOfEntities as $value) {

                        $logicalName = false;
                        if ( is_array( $value )) {
                            list( $guid, $logicalName ) = $this->get_guid_and_logicalName( $value );
                        } else {
                            $guid = $value;
                        }
                        
                        if (!$logicalName) {
                            $logicalName = $defaultLogicalName;
                        }
                      
                        $entity = new stdClass();
			$entity->logicalName = "activityparty";
			$entity->schema = array(
				"resourcespecid" => array( "type"=>"guid", "logicalName"=>"resourcespec" ),
				"partyid" => array( "type"=>"guid", "logicalName"=>$logicalName ),
				"ownerid" => array( "type"=>"guid", "logicalName"=>"systemuser" ),
				"effort" => "double",
				"ispartydeleted" => "boolean",
			);
			$entity->partyid = $guid;
			$entity->resourcespecid = "73356AB9-1244-E111-90B4-1CC1DE6D3B23";
			$entity->ownerid = "adb45933-85fd-4489-917f-3cd1131cb71b";
			$entity->effort = 1;
			$entity->ispartydeleted = "false";

			$xml .= $this->do_entity_tag( $entity, $entity->logicalName, $entity->schema );

		}

		$xml .= '</c:value>
                        </b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function get_guid_and_logicalName($value) {
            $guid = $value["guid"];
            $logicalName = $value["logicalName"];
            return array( $guid, $logicalName );
	}

	private function do_guid_value($key, $value, $logicalName) {

            $xml = $this->do_entityreference_value( $key, $value, $logicalName );
            return $xml;
	}

	private function do_entityreference_value($key, $guid, $logicalName) {
		$xml =      '<b:KeyValuePairOfstringanyType>
						<c:key>'.$key.'</c:key>
						<c:value i:type="b:EntityReference">
							<b:Id>'.$guid.'</b:Id>
							<b:LogicalName>'.$logicalName.'</b:LogicalName>
							<b:Name i:nil="true" />
						</c:value>
					</b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function do_generic_value($key, $value, $type) {

		$xml = '<b:KeyValuePairOfstringanyType>
                            <c:key>' . $key . '</c:key>
                            <c:value i:type="d:' . $type . '" '
                                . 'xmlns:d="http://www.w3.org/2001/XMLSchema">'.$value.'</c:value>
                        </b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	/**
	 * @param $value    datetime in format yyyy-MM-dd hh:mm:ss
	 */
	private function do_datetime_value($key, $value) {
		$xml = '';
		$datevalue = str_replace(" ", "T", $value) . 'Z';
		return $this->do_generic_value( $key, $datevalue, 'dateTime' );
	}

	private function do_float_value($key, $value) {
		$xml = '<b:KeyValuePairOfstringanyType>
					<c:key>' . $key . '</c:key>
                    <c:value i:type="b:Float">
                        <b:Value>' . $value . '</b:Value>
                    </c:value>
                </b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function do_money_value($key, $value) {
		$xml = '<b:KeyValuePairOfstringanyType>
					<c:key>' . $key . '</c:key>
                    <c:value i:type="b:Money">
                        <b:Value>' . $value . '</b:Value>
                    </c:value>
                </b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function do_option_value($key, $value) {

		$xml = '<b:KeyValuePairOfstringanyType>
                    <c:key>' . $key . '</c:key>
                    <c:value i:type="b:OptionSetValue">
                        <b:Value>' . $value . '</b:Value>
					</c:value>
                </b:KeyValuePairOfstringanyType>';
		return $xml;
	}
        
        private function do_columns_set($columns) {
            $xml = '<b:ColumnSet>';
            if ( "all" == $columns ) {
                $xml .= '<b:AllColumns>true</b:AllColumns>';
            } else {
                $xml .= '<b:Columns xmlns:b="http://schemas.microsoft.com/2003/10/Serialization/Arrays">';
                foreach ( $columns as $column ) {
                        $xml .= '<d:string>' . $column . '</d:string>';
                }
                $xml .= '</b:Columns>';
            }
            $xml .= '</b:ColumnSet>';
            return $xml;
        }
        
        private function do_criteria( $conditions, $schema ) {
            $xml = '<b:Criteria>
                    <b:Filters>
                      <b:FilterExpression>
                          <b:Conditions>';

            foreach ( $conditions as $condition ) {
                      $xml .= '<b:ConditionExpression>
                          <b:AttributeName>' . $condition[ "attribute" ] . '</b:AttributeName>
                          <b:Operator>' . $condition[ "operator" ] . '</b:Operator>
                          <b:Values xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays">';
                      $xml .= '<c:anyType i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">' . $condition[ "value" ] . '</c:anyType>';
                      $xml .= '</b:Values>
                      </b:ConditionExpression>';
              }

              $xml .= '</b:Conditions>
                      </b:FilterExpression>
                      </b:Filters>
                      </b:Criteria>';
              return $xml;
        }
	private function do_query_value($logicalName, $schema, $conditions = array(), $columns = "all") {
                
		$xml = '<b:KeyValuePairOfstringanyType>
		            <c:key>Query</c:key>
                            <c:value i:type="b:QueryExpression">';

                $xml .= $this->do_columns_set($columns);

		if ( ! empty( $conditions ) ) {
                    $xml .= $this->do_criteria( $conditions, $schema );
		}

		$xml .= '<b:Distinct>false</b:Distinct>
				<b:EntityName>' . $logicalName . '</b:EntityName>
				<b:LinkEntities/>
				<b:Orders />
				<b:PageInfo>
					<b:Count>0</b:Count>
					<b:PageNumber>0</b:PageNumber>
	                <b:PagingCookie i:nil="true" />
	                <b:ReturnTotalRecordCount>false</b:ReturnTotalRecordCount>
                </b:PageInfo>
                <b:NoLock>false</b:NoLock>';

		$xml .= '</c:value>
		        </b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function getRequestHeaders() {
		$head = EntityUtils::getCRMSoapHeader($this->organizationServiceURL, $this->securityData);
		return $head;
	}

	private function getExecuteHead($requestName = "Create") {
		$xml = '<s:Body>
				<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">';
		return $xml;
	}

	private function getRequestHead($requestName = "Create") {

		switch ($requestName) {
			case "Create":
				$request = "CreateRequest";
				break;
			case "Update":
				$request = "UpdateRequest";
				break;
			case "Delete":
				$request = "DeleteRequest";
				break;
			case "RetrieveMultiple":
				$request = "RetrieveMultipleRequest";
				break;
			case "Retrieve":
				$request = "RetrieveRequest";
				break;
			case "SetState":
				$request = "SetStateRequest";
				break;
		}

		$xml = '<request i:type="b:' . $request . '" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		      	<b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">';

		return $xml;
	}

	private function getRequestTail($requestName = "Create") {
		$xml = '</b:Parameters>
		      	<b:RequestId i:nil="true" />
		      	<b:RequestName>' . $requestName . '</b:RequestName>
		      	</request>';
		return $xml;
	}

	private function getExecuteTail($requestName = "Create") {
		$xml = '</Execute></s:Body>';
		return $xml;
	}
        
        private function getArrayAttribute($attributeName, array $haystack) {
            $retval = false;
            if ( isset( $haystack[ $attributeName ] ) ) {
                $retval = $haystack[ $attributeName ];
            }
            return $retval;
        }
        
        private function getXmlByType($key, $value, $type, $logicalName) {
            $xml = false;
            switch ( $type ) {
                case "datetime":
                    $xml .= $this->do_datetime_value( $key, $value );
                    break;
                case "float":
                    $xml .= $this->do_generic_value( $key, $value, "double" );
                    break;
                case "guid":
                    $xml .= $this->do_guid_value( $key, $value, $logicalName );
                    break;
                case "guid_array":
                    $xml .= $this->do_array_entities_value($key, $value, $logicalName );
                    break;
                case "int":
                    $xml .= $this->do_generic_value( $key, $value, "int" );
                    break;
                case "money":
                    $xml .= $this->do_money_value( $key, $value );
                    break;
                case "option":
                    $xml .= $this->do_option_value( $key, $value );
                    break;
                case "string":
                    $xml .= $this->do_generic_value( $key, $value, "string" );
                    break;
            }
            return $xml;
        }

        private function getAttributeType($typeOrArray) {
            $logicalName = null;
            if ( is_array( $typeOrArray ) )  {
                $type = $typeOrArray[ "type" ];
                if ( isset( $typeOrArray[ "logicalName" ] ) ) {
                    $logicalName = $typeOrArray[ "logicalName" ];
                }
            } else {
                $type = $typeOrArray;
            }
            return array( $type, $logicalName );
        }
        
	private function fetchEntityFields($object, $schema) {

                $xml = "";
		
		foreach ( $schema as $key=>$typeOrArray ) {

                    list( $type, $logicalName ) = $this->getAttributeType( $typeOrArray );

                    if ( isset( $object->{$key} ) ) {
                        $value = $object->{$key};
                        $xml .= $this->getXmlByType($key, $value, $type, $logicalName);
                    }
            }

            return $xml;
	}

	private function getRequestBody($entity, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000", $conditions = array(), $columns = "all") {

		$xml = $this->getExecuteHead( $requestName );
		$xml .= $this->getRequestHead( $requestName );

		switch ($requestName) {
			case "Retrieve":
			case "RetrieveMultiple":
				$xml .= $this->do_query_value($entity->getLogicalName(), $entity->getSchema(), $conditions, $columns);
				break;
			case "SetState":
				$xml .= $this->do_entityreference_value( 'EntityMoniker', $guid, $entity->getLogicalName() );
				$xml .= $this->do_option_value( 'State', $entity->getState(), $entity->getLogicalName() );
				$xml .= $this->do_option_value( 'Status', $entity->getStatus(), $entity->getLogicalName() );
				break;
			case "Delete":
				$xml .= $this->do_entityreference_value( 'Target', $guid, $entity->getLogicalName() );
				break;
			case "Update":
			case "Create":
				$xml .= $this->do_entity_value( 'Target', $entity );
		}

		$xml .= $this->getRequestTail( $requestName );
		$xml .= $this->getExecuteTail( $requestName );

		return $xml;
	}

	/**
	 * @param $requestName  Create, Update, Delete, Retrive, RetriveMultiple
	 */
	public function createXml($entity, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000", $conditions = array(), $columns = "all") {

		$head = $this->getRequestHeaders();
		$body = $this->getRequestBody( $entity, $requestName, $guid, $conditions, $columns );

		$envelope = $head.$body."</s:Envelope>";

		return $envelope;

	}

}
