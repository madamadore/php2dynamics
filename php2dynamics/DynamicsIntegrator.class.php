<?php
require_once(dirname(__FILE__) . '/LiveIDManager.php');
require_once(dirname(__FILE__) . '/EntityUtils.php');
require_once(dirname(__FILE__) . '/CrmXmlBuilder.php');
require_once(dirname(__FILE__) . '/CrmXmlReader.php');

/**
 * This class realize the integration bewtween PHP and Microsoft Dynamics.
 *
 * You can set debug mode on declaring a <code>global $_DEBUG_MODE</code> variable and set it to true.
 *
 * To use
 */
class DynamicsIntegrator
{
 	private static $liveIDUsername;
	private static $liveIDPassword;
	private static $organizationServiceURL;
	private static $securityData;

	private static $instance;
        
	private function __construct()
	{
            $json_file = file_get_contents(dirname(__FILE__) . '/config.json');
            $jfo = json_decode($json_file);
            
            self::$liveIDUsername = $jfo->username;
            self::$liveIDPassword = $jfo->password;
            self::$organizationServiceURL = $jfo->url;

            self::createSecurityData(self::$liveIDUsername, self::$liveIDPassword);
	}

	public static function getInstance()
	{
            if ( ! self::$instance) {
                self::$instance = new self();
            }
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

	private function doStateRequest( $state, $status, $guid, $logicalName ) {

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
               					<a:Value>' . $state . '</a:Value>
               				</c:value>
                                    </a:KeyValuePairOfstringanyType>
                                    <a:KeyValuePairOfstringanyType>
               				<c:key>Status</c:key>
               				<c:value i:type="a:OptionSetValue">
               					<a:Value>' . $status . '</a:Value>
               				</c:value>
                                    </a:KeyValuePairOfstringanyType>
               			</a:Parameters>
                                <a:RequestId i:nil="true" />
                                <a:RequestName>SetState</a:RequestName>
                            </request>
                            </Execute>
                        </s:Body>';

		$envelope = $head.$xml."</s:Envelope>";

		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname, 0, $pos);
		$response = LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $envelope);

		return $response;
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
            
            if ($entity->getLogicalName() == "serviceappointment" && $requestName == "Create" ) {
                
            }
                
            if ($_DEBUG_MODE) {
                $this->displayXml( $envelope );
            }
                
            $response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, DynamicsIntegrator::$organizationServiceURL, $envelope);
            
            if ( $_DEBUG_MODE ) {
                $this->displayXml( $response );
            }
            
            return $response;
	}

	/**
	 * @param   resources   array of GUID
	 */
	public function doAvaibilityRequest($resources, $start_date, $start_time, $end_date, $end_time, $gap = "1 hour")
	{
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

            return $response;
	}
        
        private function displayXml($xml) {
            echo "<pre>";
            fwrite( STDERR, print_r( $xml, TRUE ) );
            echo "</pre><br />";
        }

}
