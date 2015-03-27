<?php
require_once(dirname(__FILE__) . '/dynamics/LiveIDManager.php');
require_once(dirname(__FILE__) . '/dynamics/EntityUtils.php');
require_once(dirname(__FILE__) . '/dynamics/CrmXmlBuilder.php');
require_once(dirname(__FILE__) . '/dynamics/CrmXmlReader.php');
require_once(dirname(__FILE__) . '/dynamics/Entity.class.php');

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

	private static $instance;
	private static $debug_mode = false;

	private function __construct($serviceURL = null, $IDUsername = null, $IDPassword = null)
	{
            if ( null !== $serviceURL ) {
                self::$organizationServiceURL = $serviceURL;
            }
            if (null !== $IDUsername) { 
                self::$liveIDUsername = $IDUsername;
            }
            if (null !== $IDPassword) { 
                self::$liveIDPassword = $IDPassword;
            }
            self::createSecurityData();
	}

	public static function getInstance($IDUsername = null, $IDPassword = null, $serviceURL = null)
	{
            if ( is_null( self::$instance ) )
            {
                self::$instance = new self( $serviceURL = null, $IDUsername, $IDPassword );
            }

            return self::$instance;
	}

	public static function createSecurityData() {

            try {
                $liveIDManager      = new LiveIDManager();
                self::$securityData = $liveIDManager->authenticateWithLiveID( self::$organizationServiceURL, self::$liveIDUsername, self::$liveIDPassword );
            } catch (Exception $ex) {
                self::$securityData = null;
            }
	}

	public static function getSecurityData() {
            return self::$securityData;
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

	

	private function emptyObj($obj) {
		foreach ( $obj as $k ) {
			return false;
		}
		return true;
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
