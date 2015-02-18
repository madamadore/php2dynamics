<?php
require_once( dirname( __FILE__ ) . '/dynamics/DynamicsIntegrator.class.php' );

class CRMIntegrator {

	private function make_query($xml) {

		global $_DEBUG_MODE;

		$crm_link=& new DynamicsIntegrator();

		$head = EntityUtils::getCRMSoapHeader( DynamicsIntegrator::$organizationServiceURL, DynamicsIntegrator::$securityData );

		$req_xml= "<s:Body>";
		$req_xml.= $xml;
		$req_xml.= "</s:Body>";
		$req_xml.= '</s:Envelope>';

		$domainname = substr( DynamicsIntegrator::$organizationServiceURL, 8, -1 );
		$pos = strpos($domainname, "/");
		$domainname = substr($domainname,0,$pos);

		$response = LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, DynamicsIntegrator::$organizationServiceURL, $head.$req_xml);

		return $response;
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

		$xml = '<b:KeyValuePairOfstringanyType>
                                <c:key>' . $key . '</c:key>
                                <c:value i:type="d:' . $type . '" xmlns:d="http://www.w3.org/2001/XMLSchema">'.$value.'</c:value>
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

	public function createAccount($info) {
		global $_DEBUG_MODE;

		$xml = '<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
			<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
			      <b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
			        <b:KeyValuePairOfstringanyType>
			          <c:key>Target</c:key>
			          <c:value i:type="b:Entity">
						<b:Attributes>';

		$xml .= $this->do_generic_value('name', $info->name, 'string');

		$xml .= '</b:Attributes>
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

		$response = $this->make_query($xml);

		if ($_DEBUG_MODE) {
			echo var_dump($response);
			echo "<br/>";
		}

		$createResult ="";
		if ($response!=null && $response!=false) {
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

		$xml = '<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
			<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
			      <b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
			        <b:KeyValuePairOfstringanyType>
			          <c:key>Target</c:key>
			          <c:value i:type="b:Entity">
						<b:Attributes>';

		$xml .= $this->do_generic_value('firstname', $info->firstname, 'string');
		$xml .= $this->do_generic_value('lastname', $info->lastname, 'string');
		$xml .= $this->do_generic_value('emailaddress', $info->emailaddress, 'string');
		$xml .= $this->do_generic_value('mobilephone', $info->mobilephone, 'string');

		$xml .= '</b:Attributes>
			            <b:EntityState i:nil="true" />
			            <b:FormattedValues xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic" />
			            <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
			            <b:LogicalName>contact</b:LogicalName>
			            <b:RelatedEntities xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic" />
			            <b:RelatedEntities xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic" />
			          </c:value>
			        </b:KeyValuePairOfstringanyType>
			      </b:Parameters>
			      <b:RequestId i:nil="true" />
			      <b:RequestName>Create</b:RequestName>
					</request>
				</Execute>';

		$response = $this->make_query($xml);

		if ($_DEBUG_MODE) {
			echo var_dump($response);
			echo "<br/>";
		}

		$createResult ="";
		if ($response!=null && $response!=false) {
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

}
