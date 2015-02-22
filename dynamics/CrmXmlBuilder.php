<?php

class CrmXmlBuilder {

	private $organizationServiceURL;
	private $securityData;

	public function __construct($organizationServiceURL, $securityData) {
		$this->organizationServiceURL = $organizationServiceURL;
		$this->securityData = $securityData;
	}

	private function do_entity_array_value($value) {

		list($guid, $logicalName) = $this->do_guid_array( $value );

		$effort = 1;
		$resourcespecid = "73356AB9-1244-E111-90B4-1CC1DE6D3B23";
		$ownerid = "adb45933-85fd-4489-917f-3cd1131cb71b";

		$xml = '<b:Entity>
					<b:Attributes>
						<b:KeyValuePairOfstringanyType>
							<c:key>partyid</c:key>
							<c:value i:type="b:EntityReference">
								<b:Id>' . $guid . '</b:Id>
								<b:LogicalName>' . $logicalName . '</b:LogicalName>
								<b:Name i:nil="true" />
							</c:value>
						</b:KeyValuePairOfstringanyType>
						<b:KeyValuePairOfstringanyType>
							<c:key>resourcespecid</c:key>
							<c:value i:type="b:EntityReference">
								<b:Id>' . $resourcespecid . '</b:Id>
								<b:LogicalName>resourcespec</b:LogicalName>
								<b:Name i:nil="true" />
							</c:value>
						</b:KeyValuePairOfstringanyType>
						<b:KeyValuePairOfstringanyType>
							<c:key>ownerid</c:key>
							<c:value i:type="b:EntityReference">
								<b:Id>' . $ownerid . '</b:Id>
								<b:LogicalName>systemuser</b:LogicalName>
								<b:Name i:nil="true" />
							</c:value>
						</b:KeyValuePairOfstringanyType>
						<b:KeyValuePairOfstringanyType>
							<c:key>effort</c:key>
							<c:value i:type="d:double" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$effort.'</c:value>
						</b:KeyValuePairOfstringanyType>
						<b:KeyValuePairOfstringanyType>
							<c:key>ispartydeleted</c:key>
							<c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value>
						</b:KeyValuePairOfstringanyType>
					</b:Attributes>
		            <b:EntityState i:nil="true" />
		            <b:FormattedValues />
		            <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
		            <b:LogicalName>activityparty</b:LogicalName>
		            <b:RelatedEntities />
				</b:Entity>';
		// <b:Id>F2089712-571D-E311-AF02-3C4A92DBD80A</b:Id>
		return $xml;
	}

	private function do_array_entities_value($key, $arrayOfEntities) {

		$xml = '<b:KeyValuePairOfstringanyType>
					<c:key>'.$key.'</c:key>
					<c:value i:type="b:ArrayOfEntity">';

		foreach ($arrayOfEntities as $value) {
			$xml .= $this->do_entity_array_value( $value );
		}

		$xml .= '</c:value>
				</b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function do_guid_array($value) {
		$guid = $value["guid"];
		$logicalName = $value["logicalName"];
		return array( $guid, $logicalName );
	}

	private function do_guid_value($key, $value) {

		list($guid, $logicalName) = $this->do_guid_array( $value );

		$xml = '<b:KeyValuePairOfstringanyType>
						<c:key>'.$key.'</c:key>
						<c:value i:type="b:EntityReference">';
		$xml .= $this->do_entityreference_content($guid, $logicalName);
		$xml .= '</c:value>
				</b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function do_entityreference_content($guid, $logicalName) {
		$xml = '<b:Id>'.$guid.'</b:Id>
				<b:LogicalName>'.$logicalName.'</b:LogicalName>
				<b:Name i:nil="true" />';
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

	private function getRequestHeaders() {
		$head = EntityUtils::getCRMSoapHeader($this->organizationServiceURL, $this->securityData);
		return $head;
	}

	private function getHeadBody($requestName = "Create") {

		$key .= '<c:key>Target</c:key>';
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
				$key .= '<c:key>Query</c:key>';
				break;
			case "Retrive":
				$request = "RetriveRequest";
				$key .= '<c:key>Query</c:key>';
				break;
		}


		$xml = '<s:Body>
				<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
				<request i:type="b:' . $request . '" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		      	<b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
		        <b:KeyValuePairOfstringanyType>' . $key;

		return $xml;
	}


	private function getHeadRequestBody($requestName = "Create") {
		switch ($requestName) {
			case "Update":
			case "Create":
				$xml .= '<c:value i:type="b:Entity">
		              		<b:Attributes>';
				break;
			case "Delete":
				$xml .= '<c:value i:type="b:EntityReference">';
				break;
		}

		return $xml;
	}

	private function getTailRequestBody($entityLogicalName, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000") {
		switch ($requestName) {
			case "Update":
			case "Create":
				$xml .= '</b:Attributes>
							<b:EntityState i:nil="true" />
		            		<b:FormattedValues />
		            		<b:Id>' . $guid . '</b:Id>
		            		<b:LogicalName>' . $entityLogicalName . '</b:LogicalName>
		                    <b:RelatedEntities />
		                </c:value>';
				break;
			case "Delete":
				$xml .= '</c:value>';
				break;
		}

		return $xml;
	}

	private function getTailBody($entityLogicalName, $requestName = "Create") {

		$xml = '</b:KeyValuePairOfstringanyType>
		      	</b:Parameters>
		      	<b:RequestId i:nil="true" />
		      	<b:RequestName>' . $requestName . '</b:RequestName>
				</request>
				</Execute>
				</s:Body></s:Envelope>';
		return $xml;
	}

	private function fetchEntityFields($entity) {
		$schema = $entity->schema;
		foreach ( $schema as $key=>$type ) {

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
						$xml .= $this->do_guid_value( $key, $value );
						break;
					case "guid_array":
						$xml .= $this->do_array_entities_value($key, $value );
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

	private function getEntityBody($entity, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000") {

		switch ($requestName) {
			case "Update":
			case "Create":
				$xml = $this->fetchEntityFields( $entity );
				break;
			case "Delete":
				$xml = $this->do_entityreference_content( $guid, $entity->logicalName );
				break;
		}

		return $xml;

	}

	/**
	 * @param $requestName  Create, Update, Delete, Retrive, RetiveMultiple
	 */
	public function createXml($entity, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000") {

		global $_DEBUG_MODE;

		$head = $this->getRequestHeaders();
		$body = $this->getHeadBody( $requestName );
		$body .= $this->getHeadRequestBody( $requestName );
		$body .= $this->getEntityBody( $entity, $requestName, $guid );
		$body .= $this->getTailRequestBody( $entity->logicalName, $requestName, $guid );
		$body .= $this->getTailBody( $entity->logicalName, $requestName );

		$envelope = $head.$body;

		if ($_DEBUG_MODE) {
			echo "<pre>";
			echo $envelope;
			echo "</pre>";
			echo "<br/>";
		}

		return $envelope;

	}
}
