<?php
require_once(dirname(__FILE__) . '/dynamics/LiveIDManager.php');
require_once(dirname(__FILE__) . '/dynamics/EntityUtils.php');
require_once(dirname(__FILE__) . '/dynamics/CrmXmlBuilder.php');
require_once(dirname(__FILE__) . '/dynamics/CrmXmlReader.php');

require_once(dirname(__FILE__) . '/entities/ReadOnlyEntity.class.php');
require_once(dirname(__FILE__) . '/entities/Entity.class.php');
require_once(dirname(__FILE__) . '/entities/Appointment.class.php');
require_once(dirname(__FILE__) . '/entities/Booking.class.php');
require_once(dirname(__FILE__) . '/entities/Bike.class.php');
require_once(dirname(__FILE__) . '/entities/BikeModel.class.php');
require_once(dirname(__FILE__) . '/entities/Contact.class.php');
require_once(dirname(__FILE__) . '/entities/Equipment.class.php');
require_once(dirname(__FILE__) . '/entities/Price.class.php');
require_once(dirname(__FILE__) . '/entities/Product.class.php');

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
	private static $liveIDPassword = "2015__TB";
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

	public static $_STATE = array( "Open" => "0", "Closed" => "1", "Canceled" => "2", "Scheduled" => "3" );
	public static $_STATUS = array( "Tentative" => "2", "Awaiting Deposit" => "1", "Completed" => "8",
	                                "Canceled" => "9", "Confirmed" => "4", "In Progress" => "6", "No Show" => "10" );
	public static $_LANGUAGE = array( "EN" => "108600000", "NL" => "108600001", "DE" => "108600002", "IT" => "108600003", "ES" => "108600004" );
	public static $_SERVICE_TYPE = array( "scheduled_tour" => "108600000", "private_tour" => "108600001", "bike_rental" => "108600002" );
	public static $_BIKE_PRODUCT_ID = "11447825-AE42-E111-90B4-1CC1DE6D3B23";
	public static $_SITE_ID = "D68CDA78-D10E-E111-926A-1CC1DE086845";
	public static $_SERVICE_ID = array( "Rental" => "74356ab9-1244-e111-90b4-1cc1de6d3b23", "Tour" => "1D3E19B5-EFDA-E111-B52D-D4856451DC79" );

	public static $_BOOKING_TYPE = array( "Web" => "108600000", "Direct" => "108600003", "Partner" => "108600001",
	                                      "Third Party" => "108600002", "Local Party" => "108600004");

	private function __construct($IDUsername = null, $IDPassword = null)
	{
            self::createSecurityData($IDUsername, $IDPassword);
	}

	public static function getInstance($IDUsername = null, $IDPassword = null)
	{
            self::$instance = new self( $IDUsername, $IDPassword );
            return self::$instance;
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

	public static function getSecurityData() {
		return self::$securityData;
	}

	public function createBooking($booking, $state="Scheduled", $status = "Confirmed") {

		$response = $this->doRequest( $booking );

		echo "----";
		echo $response;
		echo "----";

		$guid = $this->getCreatedId( $response );
		$this->setState( $state, $status, $guid, 'serviceappointment' );

		return $createdId;
	}

	private function getCreatedId($response) {
		$responsedom = new DomDocument();
		$responsedom->loadXML($response);

		$fault = $responsedom->getElementsbyTagName("fault");

		if ( count( (array) $fault) > 0 ) {

			return false;

		} else {

			$keyValuePairs = $responsedom->getElementsbyTagName( "KeyValuePairOfstringanyType" );
			foreach ( $keyValuePairs as $keyValuePair ) {

				$key   = $keyValuePair->getElementsbyTagName( "key" )->item( 0 )->textContent;
				$value = $keyValuePair->getElementsbyTagName( "value" )->item( 0 )->textContent;

				$retval = $value;
			}
		}

		return $retval;
	}

	private function setState( $state, $status, $guid, $logicalName ) {

		$head = EntityUtils::getCRMSoapHeader(self::$organizationServiceURL, self::$securityData);
		$xml = '<s:Body>
				<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
				<request i:type="b:SetStateRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:b="http://schemas.microsoft.com/crm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
               		<a:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
               			<a:KeyValuePairOfstringanyType>
               				<c:key>EntityMoniker</c:key>
               				<c:value i:type="a:EntityReference">
               					<a:Id>' . $guid . '</a:Id>
               					<a:LogicalName>' . $logicalName . '</a:LogicalName>
               					<a:Name i:nil="true" />
               				</c:value>
               			</a:KeyValuePairOfstringanyType>
               			<a:KeyValuePairOfstringanyType>
               				<c:key>State</c:key>
               				<c:value i:type="a:OptionSetValue">
               					<a:Value>' . DynamicsIntegrator::$_STATE[ $state ] . '</a:Value>
               				</c:value>
               			</a:KeyValuePairOfstringanyType>
               			<a:KeyValuePairOfstringanyType>
               				<c:key>Status</c:key>
               				<c:value i:type="a:OptionSetValue">
               					<a:Value>' . DynamicsIntegrator::$_STATUS[ $status ] . '</a:Value>
               				</c:value>
               			</a:KeyValuePairOfstringanyType>
               			</a:Parameters>
						<a:RequestId i:nil="true" />
               			<a:RequestName>SetState</a:RequestName>
				</request>
				</Execute></s:Body>';

		$envelope = $head.$xml."</s:Envelope>";

		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);
		$response = LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $envelope);

		return $response;
	}

	private function emptyObj($obj) {
		foreach ( $obj as $k ) {
			return false;
		}
		return true;
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
		$nodes = $responsedom->getElementsbyTagName("keyvaluepairofstringanytype");
		$created_id = false;
		foreach ($nodes as $node) {
			$created_id =  $node->getElementsbyTagName("value")->item(0)->textContent;
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
					echo "key=".$key." value=".$value."<br/>";
				}
//                        $accountsArray[] = $reservation;
			}
		} else {
			return false;
		}
		return $accountsArray;

	}

	public function getServices() {

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
					                <a:AllColumns>true</a:AllColumns>
					              </a:ColumnSet>

					              <a:Distinct>false</a:Distinct>
					              <a:EntityName>service</a:EntityName>
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
					echo "key=".$key." value=".$value."<br/>";
				}
//                        $accountsArray[] = $reservation;
			}
		} else {
			return false;
		}
		return $accountsArray;

	}

	public function getBooking($guid) {

            $booking = new Booking( "emptyobject" );
            $response = $this->doRequest( $booking, "RetriveMultiple", $guid );

            $responsedom = new DomDocument();
            $responsedom->loadXML($response);
            
            $arrayOfObjects = array();
            $entities = $responsedom->getElementsbyTagName("Entity");
            foreach ($entities as $entity ) {
                
                $object = new stdClass();
                $nodes = $entity->getElementsbyTagName("keyvaluepairofstringanytype");
                
                foreach( $nodes as $node ) {
                    $key =  $node->getElementsbyTagName("key")->item(0)->textContent;
                    $value =  $node->getElementsbyTagName("value")->item(0)->textContent;
                    $object->{$key} = $value;
                }
                
                $arrayOfObjects[] = $object;
            }
            
            return $arrayOfObjects;
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

		$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $head.$request);

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

        /**
         * @param $conditions An array of array. 
         *                      Each sub-array has: 
         *                          ["attribute", "operator", "value"]. 
         *                      Possible operator:
         *                          Equal, Like, GreaterThan, LessThan, NotEqual
         */
	public function doRequest($entity, $requestName = "Create", $guid = "00000000-0000-0000-0000-000000000000", $conditions = array(), $columns = "all") {

            global $_DEBUG_MODE;

            $domainname = substr( DynamicsIntegrator::$organizationServiceURL,8,-1 );
            $pos = strpos($domainname, "/");
            $domainname = substr($domainname,0,$pos);

            $xmlbuilder = new CrmXmlBuilder( DynamicsIntegrator::$organizationServiceURL, DynamicsIntegrator::$securityData );
            $envelope = $xmlbuilder->createXml( $entity, $requestName, $guid, $conditions, $columns );
            
            if ($_DEBUG_MODE) {
                $this->displayXml( $envelope );
            }
                
            $response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, DynamicsIntegrator::$organizationServiceURL, $envelope);

            if ( $_DEBUG_MODE ) {
                $this->displayXml( $response );
            }
            
            return $response;
	}
        
        private function displayXml($xml) {
            echo "<pre>";
            echo $xml; 
            echo "</pre><br />";
        }

}
