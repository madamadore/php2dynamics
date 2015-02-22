<?php
require_once(dirname(__FILE__) . '/dynamics/LiveIDManager.php');
require_once(dirname(__FILE__) . '/dynamics/EntityUtils.php');

require_once(dirname(__FILE__) . '/entities/Entity.php');
require_once(dirname(__FILE__) . '/entities/Account.class.php');
require_once(dirname(__FILE__) . '/entities/Contact.class.php');
require_once(dirname(__FILE__) . '/entities/Booking.class.php');

/**
 * This class realize the integration bewtween PHP and Microsoft Dynamics.
 *
 * You can set debug mode on declaring a <code>global $_DEBUG_MODE</code> variable and set it to true.
 *
 * To use
 */
class DynamicsIntegrator
{
// 	private static $liveIDUsername = "website@topbike.onmicrosoft.com";
//	private static $liveIDPassword = "TBR_2k13";
// 	private static $liveIDUsername = "matteo@topbike.onmicrosoft.com";
//	private static $liveIDPassword = "Habo6863";

 	private static $liveIDUsername = "admin@topbike.onmicrosoft.com";
	private static $liveIDPassword = "2012Topbike";
	private static $organizationServiceURL = "https://topbike.crm4.dynamics.com/XRMServices/2011/Organization.svc";
	private static $securityData;
	private static $alphabet = array(   'a', 'b', 'c', 'd', 'e',
									    'f', 'g', 'h', 'i', 'j',
		                                'k', 'l', 'm', 'n', 'o',
									    'p', 'q', 'r', 's', 't',
		                                'u', 'v', 'w', 'x', 'y',
										'z' );

	private static $instance;
	private static $debug_mode = false;

	public static $_STATE = array( "Open" => "0", "Closed" => "1", "Canceled" => "2", "Scheduled" => 3 );
	public static $_STATUS = array("Tentative" => "2", "Awaiting Deposit" => "1", "Completed" => "8",
	                                "Canceled" => "9", "Confirmed" => "4", "In Progress" => "6", "No Show" => "10" );
	public static $_LANGUAGE = array( "EN"=>"108600000", "NL"=>"108600001", "DE"=>"108600002", "IT"=>"108600003", "ES"=>"108600004" );
	public static $_SERVICE_TYPE = array( "scheduled_tour"=>"108600000", "private_tour"=>"108600001", "bike_rental"=>"108600002" );
	public static $_BIKE_PRODUCT_ID = "11447825-AE42-E111-90B4-1CC1DE6D3B23";
	public static $_SITE_ID = "D68CDA78-D10E-E111-926A-1CC1DE086845";
	public static $_SERVICE_ID = "1D3E19B5-EFDA-E111-B52D-D4856451DC79";
	public static $_BOOKING_TYPE = "108600000";

	private function __construct($IDUsername = null, $IDPassword = null)
	{
		self::createSecurityData($IDUsername, $IDPassword);
	}

	public static function createSecurityData($IDUsername = null, $IDPassword = null) {

		try {
			if (null === $IDUsername) $IDUsername = self::$liveIDUsername;
			if (null === $IDPassword) $IDPassword = self::$liveIDPassword;

			$liveIDManager      = new LiveIDManager();
			self::$securityData = $liveIDManager->authenticateWithLiveID( self::$organizationServiceURL, $IDUsername, $IDPassword );
		} catch (Exception $ex) {
			self::$securityData = null;
		}
	}

	public static function getInstance($IDUsername = null, $IDPassword = null)
	{
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self( $IDUsername, $IDPassword );
		}

		self::$instance = new self( $IDUsername, $IDPassword );

		return self::$instance;
	}

	public static function getSecurityData() {
		return self::$securityData;
	}

	private function do_entity_array_value($guid, $logicalName = "equipment") {
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

	private function do_array_entities_value($key, $arrayOfEntities, $logicalName = "equipment") {

		$xml = '<b:KeyValuePairOfstringanyType>
					<c:key>'.$key.'</c:key>
					<c:value i:type="b:ArrayOfEntity">';

		foreach ($arrayOfEntities as $guid) {
			$xml .= $this->do_entity_array_value( $guid, $logicalName );
		}

		$xml .= '</c:value>
				</b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function do_guid_value($key, $guid, $logicalName) {

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
		$head = EntityUtils::getCRMSoapHeader(self::$organizationServiceURL, self::$securityData);
		return $head;
	}

	private function getHeadBody($requestName = "Create") {
		$xml = '<s:Body>
				<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
				<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		      	<b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
		        <b:KeyValuePairOfstringanyType>
		          <c:key>Target</c:key>';

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
						$xml .= $this->do_guid_value( $key, $value, $entity->logicalName );
						break;
					case "guid_array":
						$xml .= $this->do_array_entities_value($key, $value, "equipment" );
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

	private function doRequest($entity, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000") {

		global $_DEBUG_MODE;

		$head = $this->getRequestHeaders();
		$body = $this->getHeadBody( $requestName );
		$body .= $this->getHeadRequestBody( $requestName );
		$body .= $this->getEntityBody( $entity, $requestName, $guid );
		$body .= $this->getTailRequestBody( $entity->logicalName, $requestName, $guid );
		$body .= $this->getTailBody( $entity->logicalName, $requestName );

		$domainname = substr(DynamicsIntegrator::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$envelope = $head.$body;

		if ($_DEBUG_MODE) {
			echo "<pre>";
			echo $envelope;
			echo "</pre>";
			echo "<br/>";
		}

		$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, DynamicsIntegrator::$organizationServiceURL, $envelope);

		if ($_DEBUG_MODE) {
			echo $response;
			echo "<br/>";
		}

		return $response;
	}

	public function createBooking($booking) {

		$response = $this->doRequest($booking);

		$responsedom = new DomDocument();
		$responsedom->loadXML($response);
		$nodes = $responsedom->getElementsbyTagName("keyvaluepairofstringanytype");

		echo "-------";
		echo var_dump($nodes);
		echo "-------";

		$created_id = false;
		foreach ($nodes as $node) {
			echo $node->textContent;
			$created_id =  $node->getElementsbyTagName("c:value")->item(0)->textContent;
		}

		return $created_id;
	}

	public function updateContact($contact, $guid) {

		$response = $this->doRequest( $contact, "Update", $guid );

		$responsedom = new DomDocument();
		$responsedom->loadXML($response);
		$nodes = $responsedom->getElementsbyTagName("b:keyvaluepairofstringanytype");
		$created_id = false;
		foreach ($nodes as $node) {
			$created_id =  $node->getElementsbyTagName("c:value")->item(0)->textContent;
		}

		return $created_id;
	}

	public function createContact($contact) {

		$response = $this->doRequest($contact);

		$responsedom = new DomDocument();
		$responsedom->loadXML($response);
		$nodes = $responsedom->getElementsbyTagName("b:keyvaluepairofstringanytype");
		$created_id = false;
		foreach ($nodes as $node) {
			$created_id =  $node->getElementsbyTagName("c:value")->item(0)->textContent;
		}

		return $created_id;
	}

	public function deleteContact($guid) {
		$response = $this->doRequest( $contact, "Delete", $guid );
	}

	public function getTours($start_date, $end_date, $language, $product_id) {}

	public function getContacts() {

		global $_DEBUG_MODE;

		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$head = EntityUtils::getCRMSoapHeader(self::$organizationServiceURL, self::$securityData);

		$request = '<s:Body>
						<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
					      <request i:type="a:RetrieveMultipleRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts">
					        <a:Parameters xmlns:b="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
					          <a:KeyValuePairOfstringanyType>
					            <b:key>Query</b:key>
					            <b:value i:type="a:QueryExpression">
					              <a:ColumnSet>
					                <a:AllColumns>false</a:AllColumns>
					                <a:Columns xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
                  						<c:string>fullname</c:string>
                  						<c:string>emailaddress1</c:string>
                					</a:Columns>
					              </a:ColumnSet>

									<a:Criteria>
				                      <a:Filters>
				                        <a:FilterExpression>
				                          <a:Conditions>
				                            <a:ConditionExpression>
				                              <a:AttributeName>fullname</a:AttributeName>
				                              <a:Operator>Equal</a:Operator>
				                              <a:Values xmlns:b="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
				                                <b:anyType i:type="c:string" xmlns:c="http://www.w3.org/2001/XMLSchema">User Test</b:anyType>
				                              </a:Values>
				                            </a:ConditionExpression>
				                          </a:Conditions>
				                        </a:FilterExpression>
				                      </a:Filters>
				                    </a:Criteria>

					              <a:Distinct>false</a:Distinct>
					              <a:EntityName>contact</a:EntityName>
					              <a:LinkEntities/>
					              <a:Orders />
					              <a:PageInfo>
					                <a:Count>0</a:Count>
					                <a:PageNumber>0</a:PageNumber>
					                <a:PagingCookie i:nil="true" />
					                <a:ReturnTotalRecordCount>false</a:ReturnTotalRecordCount>
					              </a:PageInfo>
					              <a:NoLock>false</a:NoLock>
					            </b:value>
					          </a:KeyValuePairOfstringanyType>
					        </a:Parameters>
					        <a:RequestId i:nil="true" />
					        <a:RequestName>RetrieveMultiple</a:RequestName>
					      </request>
					    </Execute>
					</s:Body></s:Envelope>';

		$response = LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $head.$request);

		if ($_DEBUG_MODE) {
			echo "<pre>";
			echo var_dump($response);
			echo "</pre>";
			echo "<br/>";
		}

		$accountsArray = array();
		if($response != null && $response !="") {

			$responsedom = new DomDocument();
			$responsedom->loadXML($response);
			$entities = $responsedom->getElementsbyTagName("Entity");
			foreach($entities as $entity) {
				$reservation = array();
				$kvptypes = $entity->getElementsbyTagName("KeyValuePairOfstringanyType");
				echo "<br><br>NUOVA ENTITY:<br><br>";
				foreach($kvptypes as $kvp) {
					$key =  $kvp->getElementsbyTagName("key")->item(0)->textContent;
					$value =  $kvp->getElementsbyTagName("value")->item(0)->textContent;
					echo "<br><br>NUOVA RIGA:<br><br>";
					echo "key=".$key." value=".$value;
				}
//                        $accountsArray[] = $reservation;
			}
		} else {
			return false;
		}
		return $accountsArray;

	}

	private function validateDate($date) {
		$mysql_dattime_regex = "/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$/";

		if ( ! preg_match($mysql_dattime_regex, $date)) {
			error_log("start_date in formato non valido. Deve essere yyyy-mm-dd hh:mm:ss");
			return false;
		} else {
			$dates = explode(" ", trim( $date ));
			$date_part = $dates[0];
			$time_part = $dates[1];
		}

		return array( $date_part, $time_part );
	}

	/**
	 * @param   resources   array of GUID
	 */
	public function checkAvailability($resources, $start_date, $end_date, $gap = "1 hour")
	{
		list($start_date, $start_time) = $this->validatedate( $start_date );
		list($end_date, $end_time) = $this->validatedate( $end_date );

		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$st = new DateTime( $start_time );
		$st->modify( '-' . $gap );
		$start_time = $st->format( 'H:i:s' );

		$et = new DateTime( $end_time );
		$et->modify( '+' . $gap );
		$end_time = $et->format( 'H:i:s' );

		$head = EntityUtils::getCRMSoapHeader(self::$organizationServiceURL, self::$securityData);
		$request = '<s:Body>
                <Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                <request i:type="b:QueryMultipleSchedulesRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:b="http://schemas.microsoft.com/crm/2011/Contracts">
                    <a:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
                        <a:KeyValuePairOfstringanyType>
                            <c:key>ResourceIds</c:key>
                            <c:value i:type="d:ArrayOfguid" xmlns:d="http://schemas.microsoft.com/2003/10/Serialization/Arrays">';
		foreach ($resources as $resource)
			$request .= '<d:guid>'.$resource.'</d:guid>';
		$request .= '</c:value>
                        </a:KeyValuePairOfstringanyType>
                        <a:KeyValuePairOfstringanyType>
                            <c:key>Start</c:key>
                            <c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$start_date.'T'.$start_time.'Z</c:value>
                        </a:KeyValuePairOfstringanyType>
                        <a:KeyValuePairOfstringanyType>
                            <c:key>End</c:key>
                            <c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$end_date.'T'.$end_time.'Z</c:value>
                        </a:KeyValuePairOfstringanyType>
                        <a:KeyValuePairOfstringanyType>
                            <c:key>TimeCodes</c:key>
                            <c:value i:type="d:ArrayOfTimeCode" xmlns:d="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
                                <d:TimeCode>Available</d:TimeCode>
                            </c:value>
                        </a:KeyValuePairOfstringanyType>
                    </a:Parameters>
                    <a:RequestId i:nil="true" />
                    <a:RequestName>QueryMultipleSchedules</a:RequestName>
                </request>
                </Execute>
            </s:Body>
            </s:Envelope>';

		echo "<pre>";
		echo $request;
		echo "</pre>";

		$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $head.$request);
		error_log("<pre>" . $response . "</pre>");

		$accountsArray = array();
		if($response!=null && $response!="") {

			$responsedom = new DomDocument();
			$responsedom->loadXML($response);
			$entities = $responsedom->getElementsbyTagName("ArrayOfTimeInfo");
			$counter = 0;
			foreach($entities as $entity) {
				$reservation = array();
				$available = true;
				$kvptypes = $entity->getElementsbyTagName("TimeInfo");
				foreach($kvptypes as $kvp) {
					$start =  $kvp->getElementsbyTagName("Start")->item(0)->textContent;
					$end =  $kvp->getElementsbyTagName("End")->item(0)->textContent;
					$timecode =  $kvp->getElementsbyTagName("TimeCode")->item(0)->textContent;
					$effort =  $kvp->getElementsbyTagName("Effort")->item(0)->textContent;

					if ($timecode != "Available") {
						$available = false;
					}

					$reservation[] = array ( "status" => $timecode,
					                         "start" => $start,
					                         "end" => $end,
					                         "effort" => $effort );
				}
				$guid = $resources[ $counter ];

				$avaibilityReponse = array( "avaibility" => $available, "details" => $reservation);
				$accountsArray[ $guid ] = $avaibilityReponse;
				$counter++;
			}
		} else {
			return false;
		}

		return $accountsArray;
	}

	public function getBooking($guid) {}
	public function deleteBooking() {}

	public function getTour($guid) {}

	public function getContact($guid) {}

}
