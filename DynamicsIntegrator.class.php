<?php
require_once(dirname(__FILE__) . '/dynamics/LiveIDManager.php');
require_once(dirname(__FILE__) . '/dynamics/EntityUtils.php');

require_once(dirname(__FILE__) . '/entities/Entity.php');
require_once(dirname(__FILE__) . '/entities/Account.class.php');
require_once(dirname(__FILE__) . '/entities/Contact.class.php');

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

	private function do_entity_array_value($entity) {
		$xml = '<b:Entity>
					<b:Attributes>
						<b:KeyValuePairOfstringanyType>
						<c:key>partyid</c:key>
						<c:value i:type="b:EntityReference">
							<b:Id>' . $entity->ID . '</b:Id>
							<b:LogicalName>equipment</b:LogicalName>
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
		return $xml;
	}

	private function do_array_entities_value($key, $arrayOfEntities, $logicalName) {

		$xml = '<b:KeyValuePairOfstringanyType>
					<c:key>'.$key.'</c:key>
					<c:value i:type="b:ArrayOfEntity">';

		foreach ($arrayOfEntities as $entity) {
			$xml .= do_entity_array_value($entity);
		}

		$xml .= '</c:value>
				</b:KeyValuePairOfstringanyType>';
		return $xml;
	}

	private function do_guid_value($key, $guid, $logicalName) {

		$xml = '<b:KeyValuePairOfstringanyType>
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

		$xml = '<b:KeyValuePairOfstringanyType><c:key>' . $key . '</c:key><c:value i:type="d:' . $type . '" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$value.'</c:value></b:KeyValuePairOfstringanyType>';
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

	private function getHeadBody() {
		$xml = '<s:Body>
				<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
				<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		      	<b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
		        <b:KeyValuePairOfstringanyType>
		          <c:key>Target</c:key>
		          <c:value i:type="b:Entity">
		              <b:Attributes>';
		return $xml;
	}

	private function getTailBody($entityLogicalName, $requestName = "Create") {
		$xml = '</b:Attributes>
		            <b:EntityState i:nil="true" />
		            <b:FormattedValues />
		            <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
		            <b:LogicalName>' . $entityLogicalName . '</b:LogicalName>
		            <b:RelatedEntities />
		          </c:value>
		        </b:KeyValuePairOfstringanyType>
		      	</b:Parameters>
		      	<b:RequestId i:nil="true" />
		      	<b:RequestName>' . $requestName . '</b:RequestName>
				</request>
				</Execute>
				</s:Body></s:Envelope>';
		return $xml;
	}

	private function getEntityBody($entity) {
		$xml = "";
		foreach ($entity->schema as $key=>$tipology) {
			$value = $entity->{$key};
			switch ($tipology[0]) {
				case "string":
					$xml .= $this->do_generic_value($key, $value, "string");
					break;
				case "money":
					$xml .= $this->do_money_value($key, $value);
					break;
				case "option":
					$xml .= $this->do_option_value($key, $value);
					break;
				case "guid":
					$xml .= $this->do_guid_value($key, $value, "string"); // TODO: ??
					break;
			}
		}

		return $xml;
	}

	private function doCreateRequest($entity) {

		global $_DEBUG_MODE;

		$head = $this->getRequestHeaders();
		$body = $this->getHeadBody();
		$body .= $this->getEntityBody($entity);
		$body .= $this->getTailBody($entity->logicalName);

		$domainname = substr(DynamicsIntegrator::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$envelope = $head.$body;

		if ($_DEBUG_MODE) {
			echo $envelope;
			echo "<br/>";
		}

		$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, DynamicsIntegrator::$organizationServiceURL, $envelope);

		if ($_DEBUG_MODE) {
			echo $response;
			echo "<br/>";
		}

		return $response;
	}

	public function createBooking($info) {
		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$head = EntityUtils::getCreateCRMSoapHeader(self::$organizationServiceURL, self::$securityData);
		$request = '<s:Body>
                    <Create xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
                    <entity xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                        <b:Attributes xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">';

		$request .= $this->do_generic_value('subject', $info->subject, 'string');
		$request .= $this->do_generic_value('tp_bookingcode', $info->bookingcode, 'string');
		$request .= $this->do_generic_value('description', $info->description, 'string');
		$request .= $this->do_generic_value('tb_materialdetails', $info->materialdetails, 'string');
		$request .= $this->do_generic_value('scheduledstart', str_replace(" ", "T", $info->scheduledstart) . 'Z', 'dateTime');
		$request .= $this->do_generic_value('scheduledend', str_replace(" ", "T", $info->scheduledend) . 'Z', 'dateTime');
		$request .= $this->do_generic_value('tb_bookingdate', str_replace(" ", "T", $info->bookingdate) . 'Z', 'dateTime');
		$request .= $this->do_generic_value('tb_participants', str_replace(" ", "T", $info->participants) . 'Z', 'int');

		$request .= $this->do_money_value('tb_topbikerevenue', $info->revenue);

		if ($info->tourprice) {
			$request .= $this->do_money_value('tb_tourprice', $info->tourprice);
		}

		$request .= $this->do_money_value('tb_deposit', $info->deposit);
		$request .= $this->do_money_value('tb_tourprice', $info->tourprice);
		$request .= $this->do_money_value('tb_openamount', $info->openamount);

		if ($info->tourid) {
			$request .= $this->do_guid_value( 'tb_tourid', $info->tourid, 'tb_tour' );
		}
		$request .= $this->do_guid_value('tb_productid', $info->productid, 'product');

		$request .= $this->do_option_value( 'tb_servicetype', $info->servicetype );

		if ($info->language) {
			$request .= $this->do_option_value( 'tb_language', $info->language );
		}

		if ($info->regardingobjectid!=null) {
			$request .= $this->do_guid_value('regardingobjectid', $info->regardingobjectid, 'contact');
		}

		$request .= $this->do_guid_value('siteid', $info->siteid, 'site');
		$request .= $this->do_guid_value('serviceid', $info->serviceid, 'service');

		$request .= $this->do_option_value( 'tb_bookingtype', $info->bookingtype );

		if (count($info->resources) > 0) {
			$request .= '
			<b:KeyValuePairOfstringanyType>
					<c:key>resources</c:key>
					<c:value i:type="b:ArrayOfEntity">';
			foreach ($info->resources as $resource) {
				/*
				$requesta .= '<a:Entity>
							   <a:Attributes>
							   <a:KeyValuePairOfstringanyType>
								 <b:key>resourceid</b:key>
								 <b:value i:type="a:EntityReference">
								   <a:Id>'.$resource.'</a:Id>
								   <a:LogicalName>materials</a:LogicalName>
								   <a:Name i:nil="true" />
								 </b:value>
							   </a:KeyValuePairOfstringanyType>
							   </a:Attributes>
							   <a:EntityState i:nil="true" />
							   <a:FormattedValues />
							   <a:Id>00000000-0000-0000-0000-000000000000</a:Id>
							   <a:LogicalName>equipment</a:LogicalName>
							   <a:RelatedEntities />
					</a:Entity>';
					*/
				$request .='
								<a:Entity>
									       <a:Attributes />
									       <a:EntityState i:nil="true" />
									       <a:FormattedValues />
									       <a:Id>'.$resource.'</a:Id>
									       <a:LogicalName>equipment</a:LogicalName>
									       <a:RelatedEntities />
								</a:Entity>
						';
			}
			$request .= 	'</c:value>
				</b:KeyValuePairOfstringanyType>';
			/*
						$request .= '<b:KeyValuePairOfstringanyType>
										<c:key>resources</c:key>
										<c:value
											i:type="d:ArrayOfguid"
											xmlns:d="http://schemas.microsoft.com/2003/10/Serialization/Arrays">';
							foreach ($info->resources as $resource) {
								$request .= '<d:guid>'.$resource.'</d:guid>';
							}
						   $request .= '</c:value></b:KeyValuePairOfstringanyType>';
			 */
		}
		$request .= '
			</b:Attributes>
                        <b:EntityState i:nil="true"/>
                        <b:FormattedValues xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
                        <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
                        <b:LogicalName>serviceappointment</b:LogicalName>
                        <b:RelatedEntities xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
                    </entity>
                    </Create>
                </s:Body>
            </s:Envelope>';

		$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $head.$request);

		$createResult ="";
		if($response!=null && $response!="") {
			preg_match('/<CreateResult>(.*)<\/CreateResult>/', $response, $matches);
			if (count($matches) > 0) {
				$createResult = $matches[1];
				// error_log("Reservation creata. GUID: ".$createResult);
			}
		} else {
			$createResult = false;
		}

		return $createResult;
	}

	public function createAccountBis($account) {

		return $this->doCreateRequest($account);
	}
	/**
	 */
	public function createAccount($info) {
		global $_DEBUG_MODE;

		$xml='<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
				<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		      <b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
		        <b:KeyValuePairOfstringanyType>
		          <c:key>Target</c:key>
		          <c:value i:type="b:Entity">
		              <b:Attributes>
		              <b:KeyValuePairOfstringanyType>
		                <c:key>name</c:key>
		                <c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">test account</c:value>
		              </b:KeyValuePairOfstringanyType>
		            </b:Attributes>
		            <b:EntityState i:nil="true" />
		            <b:FormattedValues />
		            <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
		            <b:LogicalName>account</b:LogicalName>
		            <b:RelatedEntities />
		          </c:value>
		        </b:KeyValuePairOfstringanyType>
		      </b:Parameters>
		      <b:RequestId i:nil="true" />
		      <b:RequestName>Create</b:RequestName>
				</request>
			</Execute>';

		$head = EntityUtils::getCRMSoapHeader(DynamicsIntegrator::$organizationServiceURL, DynamicsIntegrator::$securityData);

		$req_xml= "<s:Body>";
		$req_xml.= $xml;
		$req_xml.= "</s:Body>";
		$req_xml.= '</s:Envelope>';

		$domainname = substr(DynamicsIntegrator::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$envelope = $head.$req_xml;

		if ($_DEBUG_MODE) {
			echo "<pre>";
			echo $envelope;
			echo "</pre>";
		}

		$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, DynamicsIntegrator::$organizationServiceURL, $envelope);

		if ($_DEBUG_MODE) {
			echo $response;
			echo "<br/>";
		}

		$createResult ="";
		if($response!=null && $response!=false) {
			preg_match('/<CreateResult>(.*)<\/CreateResult>/', $response, $matches);
			if ( count($matches) > 0 ) {
				$createResult = $matches[1];
				// error_log("Contact creato. GUID: ".$createResult);
			} else {
				$createResult = false;
			}
		} else {
			$createResult = false;
		}

		return $createResult;
	}

	public function createContact($info) {

		global $_DEBUG_MODE;

		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$head = EntityUtils::getCRMSoapHeader(DynamicsIntegrator::$organizationServiceURL, DynamicsIntegrator::$securityData);
		// $head = EntityUtils::getCreateCRMSoapHeader(self::$organizationServiceURL, self::$securityData);

		$request = '<s:Body>
					<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
				<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		      <b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
		        <b:KeyValuePairOfstringanyType>
		          <c:key>Target</c:key>
		          <c:value i:type="b:Entity">
		              <b:Attributes>';

		$request .= $this->do_generic_value('fullname', $info->firstname . " " . $info->lastname, 'string');
		$request .= $this->do_generic_value('firstname', $info->firstname, 'string');
		$request .= $this->do_generic_value('lastname', $info->lastname, 'string');
		//$request .= $this->do_generic_value('emailaddress', $info->emailaddress, 'string');
		//$request .= $this->do_generic_value('mobilephone', $info->mobilephone, 'string');

		$request .= '</b:Attributes>
		            <b:EntityState i:nil="true" />
		            <b:FormattedValues xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
		            <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
		            <b:LogicalName>contact</b:LogicalName>
		            <b:RelatedEntities xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"/>
		          </c:value>
		        </b:KeyValuePairOfstringanyType>
		      </b:Parameters>
		      <b:RequestId i:nil="true" />
		      <b:RequestName>Create</b:RequestName>
				</request>
			</Execute>
				</s:Body>
				</s:Envelope>';

		/*
		$request = '<s:Body><Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
				<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		      <b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
		        <b:KeyValuePairOfstringanyType>
		          <c:key>Target</c:key>
		          <c:value i:type="b:Entity">
					<b:Attributes>
					  <b:KeyValuePairOfstringanyType>
		                <c:key>fullname</c:key>
		                <c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">Matteo Avanzini</c:value>
		              </b:KeyValuePairOfstringanyType>
		              <b:KeyValuePairOfstringanyType>
		                <c:key>firstname</c:key>
		                <c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">Matteo</c:value>
		              </b:KeyValuePairOfstringanyType>
		              <b:KeyValuePairOfstringanyType>
		                <c:key>lastname</c:key>
		                <c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">Avanzini</c:value>
		              </b:KeyValuePairOfstringanyType>
		            </b:Attributes>
		            <b:EntityState i:nil="true" />
		            <b:FormattedValues />
		            <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
		            <b:LogicalName>contact</b:LogicalName>
		            <b:RelatedEntities />
		          </c:value>
		        </b:KeyValuePairOfstringanyType>
		      </b:Parameters>
		      <b:RequestId i:nil="true" />
		      <b:RequestName>Create</b:RequestName>
				</request>
			</Execute></s:Body></s:Envelope>';
		*/

		$envelope = $head.$request;

		if ($_DEBUG_MODE) {
			echo "<pre>";
			echo $envelope;
			echo "</pre>";
		}

		$response = LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $envelope);

		if ($_DEBUG_MODE) {
			echo "<pre>";
			echo $response;
			echo "</pre>";
		}

		$createResult ="";
		if($response!=null && $response!=false) {
			preg_match('/<CreateResult>(.*)<\/CreateResult>/', $response, $matches);
			if ( count($matches) > 0 ) {
				$createResult = $matches[1];
				// error_log("Contact creato. GUID: ".$createResult);
			} else {
				$createResult = false;
			}
		} else {
			$createResult = false;
		}

		return $createResult;
	}

	public function getContacts() {

		global $_DEBUG_MODE;

		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$head = EntityUtils::getCreateCRMSoapHeader(self::$organizationServiceURL, self::$securityData);

		$request = '<s:Body>
						<RetrieveMultiple xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
							<query i:type="b:QueryExpression"
									xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts"
									xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
								<b:ColumnSet>
									<b:AllColumns>true</b:AllColumns>
								</b:ColumnSet>
								<b:Distinct>false</b:Distinct>
								<b:EntityName>Contact</b:EntityName>
								<b:LinkEntities />
								<b:Orders />
								<b:PageInfo>
									<b:Count>0</b:Count>
									<b:PageNumber>0</b:PageNumber>
									<b:PagingCookie i:nil="true" />
									<b:ReturnTotalRecordCount>false</b:ReturnTotalRecordCount>
								</b:PageInfo>
							</query>
						</RetrieveMultiple>
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

	/**
	 * @param   resources   array of GUID
	 */
	public function checkAvailability($resources, $start_date, $end_date, $start_time = "05:00:00", $end_time = "05:00:00")
	{
		$domainname = substr(self::$organizationServiceURL,8,-1);
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$st = new DateTime( $start_time );
		$st->modify( '-1 hour' );
		$start_time = $st->format( 'H:i:s' );

		$et = new DateTime( $end_time );
		$et->modify( '+1 hour' );
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

		error_log($request);
		$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, self::$organizationServiceURL, $head.$request);
		error_log($response);

		$accountsArray = array();
		if($response!=null && $response!="") {

			$responsedom = new DomDocument();
			$responsedom->loadXML($response);
			$entities = $responsedom->getElementsbyTagName("ArrayOfTimeInfo");
			foreach($entities as $entity) {
				$reservation = array();
				$kvptypes = $entity->getElementsbyTagName("TimeInfo");
				foreach($kvptypes as $kvp) {
					$start =  $kvp->getElementsbyTagName("Start")->item(0)->textContent;
					$end =  $kvp->getElementsbyTagName("End")->item(0)->textContent;
					$timecode =  $kvp->getElementsbyTagName("TimeCode")->item(0)->textContent;
					$reservation[$start] = $timecode;
				}
				$accountsArray[] = $reservation;
			}
		} else {
			return false;
		}

		return $accountsArray;
	}

	public function getBooking($guid) {}
	public function deleteBooking() {}

	public function getTours() {}
	public function getTour($guid) {}

	public function getContact($guid) {}
	public function deleteContact() {}

}
