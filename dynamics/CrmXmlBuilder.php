<?php

class CrmXmlBuilder {

	private $organizationServiceURL;
	private $securityData;

	public function __construct($organizationServiceURL, $securityData) {
		$this->organizationServiceURL = $organizationServiceURL;
		$this->securityData = $securityData;
	}

	private function do_entity_tag($entity) {
		$xml = '<b:Entity><b:Attributes>';
		$xml .= $this->fetchEntityFields( $entity );
		$xml .= '</b:Attributes>
                        <b:EntityState i:nil="true" />
                        <b:FormattedValues />
                        <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
                        <b:LogicalName>' . $entity->logicalName . '</b:LogicalName>
                        <b:RelatedEntities />
                        </b:Entity>';
	}

	private function do_entity_value($key, $entity) {

		$xml .= '<b:KeyValuePairOfstringanyType>
					<c:key>' . $key . '</c:key>
					<c:value i:type="b:Entity">
						<b:Attributes>';
		$xml .= $this->fetchEntityFields( $entity );
		$xml .= '</b:Attributes>
					<b:EntityState i:nil="true" />
	                <b:FormattedValues />
	                <b:Id>' . $entity->guid . '</b:Id>
					<b:LogicalName>' . $entity->logicalName . '</b:LogicalName>
	                <b:RelatedEntities />
				</c:value><b:KeyValuePairOfstringanyType>';
	}

	private function do_array_entities_value($key, $arrayOfEntities, $defaultLogicalName) {

		$xml = '<b:KeyValuePairOfstringanyType>
                            <c:key>'.$key.'</c:key>
                            <c:value i:type="b:ArrayOfEntity">';

		foreach ($arrayOfEntities as $value) {

			list( $guid, $logicalName ) = $this->get_guid_and_logicalName( $value );
			if (!$logicalName) {
				$logicalName = $defaultLogicalName;
			}

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

			$xml = $this->do_entity_tag( $entity );

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

		$xml = $this->do_entityreference_value( $key, $guid, $logicalName );
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
					<c:value i:type="d:' . $type . '" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$value.'</c:value>
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

	private function do_query_value($logicalName, $conditions = array(), $columns = "all") {

		$xml = '<b:KeyValuePairOfstringanyType>
		            <c:key>Query</c:key>
					<c:value i:type="b:QueryExpression">';

		$xml .= '<b:ColumnSet>';
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

		if ( ! empty( $conditions ) ) {
			$xml .= '<b:Criteria>
	                      <b:Filters>
							<b:FilterExpression>
				            <b:Conditions>';
			foreach ( $conditions as $condition ) {
				$xml .= '<b:ConditionExpression>
			            <b:AttributeName>' . $condition[ "attribute" ] . '</b:AttributeName>
			            <b:Operator>' . $condition[ "operator" ] . '</b:Operator>
			            <b:Values xmlns:b="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
			                <c:anyType i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">' . $condition[ "value" ] . '</c:anyType>
			            </b:Values>
						</b:ConditionExpression>';
			}

			$xml .= '</b:Conditions>
				            </b:FilterExpression>
						</b:Filters>
					</b:Criteria>';
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
			case "RetriveMultiple":
				$request = "RetriveMultipleRequest";
				break;
			case "Retrive":
				$request = "RetriveRequest";
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

	private function fetchEntityFields($entity) {

		$schema = $entity->schema;
		foreach ( $schema as $key=>$typeOrArray ) {

			if ( is_array( $typeOrArray ) )  {
				$type = $typeOrArray[ "type" ];
				if ( isset( $typeOrArray[ "defaultLogicalName" ] ) ) $defaultLogicalName = $typeOrArray[ "defaultLogicalName" ];
				if ( isset( $typeOrArray[ "logicalName" ] ) ) $logicalName = $typeOrArray[ "logicalName" ];
			} else {
				$type = $typeOrArray;
			}

			$value = $entity->{$key};

			if ( false != $value ) {

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
						$xml .= $this->do_array_entities_value($key, $value, $defaultLogicalName );
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
			}
		}

		return $xml;
	}

	private function getRequestBody($entity, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000", $conditions = array(), $columns = "all") {

		$xml = $this->getExecuteHead( $requestName );
		$xml .= $this->getRequestHead( $requestName );

		switch ($requestName) {
			case "Retrive":
			case "RetriveMutliple":
				$xml .= $this->do_query_value($entity->logicalName, $conditions, $columns);
				break;
			case "SetState":
				$xml .= $this->do_entityreference_value( 'EntityMoniker', $guid, $entity->logicalName );
				$xml .= $this->do_option_value( 'State', $entity->state, $entity->logicalName );
				$xml .= $this->do_option_value( 'Status', $entity->status, $entity->logicalName );
				break;
			case "Delete":
				$xml .= $this->do_entityreference_value( 'Target', $guid, $entity->logicalName );
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

		global $_DEBUG_MODE;

		$head = $this->getRequestHeaders();
		$body = $this->getRequestBody( $entity, $requestName, $guid, $conditions, $columns );

		$envelope = $head.$body."</s:Envelope>";

		if ($_DEBUG_MODE) {
			echo "<pre>";
			echo $envelope;
			echo "</pre>";
			echo "<br/>";
		}

		return $envelope;

	}
}
