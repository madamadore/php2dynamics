<?php
session_start();

class TopBikeDynamicsIntegrator 
{
	    private static $liveIDUsername = "website@topbike.onmicrosoft.com";
	    private static $liveIDPassword = "WS__2k14";
#	    private static $liveIDUsername = "admin@topbike.onmicrosoft.com";
#	    private static $liveIDPassword = "2012Topbike";
      public static $organizationServiceURL = "https://topbike.crm4.dynamics.com/XRMServices/2011/Organization.svc";
      public static $securityData;
    
      private static $instance;
        
      function __construct()
      {
        $liveIDManager = new LiveIDManager();
        self::$securityData = $liveIDManager->authenticateWithLiveID( self::$organizationServiceURL, self::$liveIDUsername, self::$liveIDPassword );
      }
        
      static function getInstance()
      {
        if ( is_null( self::$instance ) )
        {
          self::$instance = new self();
        }
        return self::$instance;
      }
      
}

#require "../class/EntityUtils.retmult.php";
require "class/EntityUtils.php";
require "class/LiveIDManager.php";

#$request = get_contact();
#$request = get_account("roma");
#$request = create_account();
$request = create_appointment();
#$request = get_appointment();

function make_query($xml) {

	$crm_link=& new TopBikeDynamicsIntegrator();

	$head = EntityUtils::getCRMSoapHeader(TopBikeDynamicsIntegrator::$organizationServiceURL, TopBikeDynamicsIntegrator::$securityData);

	$req_xml= "<s:Body>";
	$req_xml.= $xml;
	$req_xml.= "</s:Body>";
	$req_xml.= '</s:Envelope>';

	$domainname = substr(TopBikeDynamicsIntegrator::$organizationServiceURL,8,-1);
	$pos = strpos($domainname, "/");
	$domainname = substr($domainname,0,$pos);

	$response =  LiveIDManager::GetSOAPResponse("/Organization.svc", $domainname, TopBikeDynamicsIntegrator::$organizationServiceURL, $head.$req_xml);
	error_log($response);

	return $response;
}

function get_account($city) {
	
$xml='<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
      <request i:type="a:RetrieveMultipleRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts">
        <a:Parameters xmlns:b="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
          <a:KeyValuePairOfstringanyType>
            <b:key>Query</b:key>
            <b:value i:type="a:QueryExpression">
              <a:ColumnSet>
                <a:AllColumns>true</a:AllColumns>
                <a:Columns xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
                  <c:string>name</c:string>
                </a:Columns>	
              </a:ColumnSet>

                    <a:Criteria>
                      <a:Filters>
                        <a:FilterExpression>
                          <a:Conditions>
                            <a:ConditionExpression>
                              <a:AttributeName>emailaddress1</a:AttributeName>
                              <a:Operator>Equal</a:Operator>
                              <a:Values xmlns:b="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
                                <b:anyType i:type="c:string" xmlns:c="http://www.w3.org/2001/XMLSchema">info@fietseninrome.nl</b:anyType>
                              </a:Values>
                            </a:ConditionExpression>
                          </a:Conditions>
                        </a:FilterExpression>
                      </a:Filters>
                    </a:Criteria>

              <a:Distinct>false</a:Distinct>
              <a:EntityName>account</a:EntityName>
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
    </Execute>';
	
	$response_xml=make_query($xml);
	
#            echo "REQUEST: <br>".$response_xml; 
//            echo "<br>RESPONSE: <br>".$response; 
            $accountsArray = array();
            if($response_xml!=null && $response_xml!="") {

                $responsedom = new DomDocument();
                $responsedom->loadXML($response_xml);
                $entities = $responsedom->getElementsbyTagName("Entity");
                foreach($entities as $entity) {
                        $reservation = array();
                        $kvptypes = $entity->getElementsbyTagName("KeyValuePairOfstringanyType");
                        print "<br><br>NUOVA ENTITY:<br><br>";
                        foreach($kvptypes as $kvp) {
                                $key =  $kvp->getElementsbyTagName("key")->item(0)->textContent;
                                $value =  $kvp->getElementsbyTagName("value")->item(0)->textContent;					
                                print "<br><br>NUOVA RIGA:<br><br>";
                                print "key=".$key." value=".$value;
                        }
//                        $accountsArray[] = $reservation;
                }
            } else {
                return false;
            }
            return $accountsArray;

}

function get_contact() {
	
	$xml='<RetrieveMultiple xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
		<query i:type="b:QueryExpression" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
		<b:ColumnSet>
			<b:AllColumns>true</b:AllColumns>
		</b:ColumnSet>
		<b:Distinct>false</b:Distinct>
		<b:EntityName>contact</b:EntityName>
		<b:LinkEntities />
		<b:Orders />
		<b:PageInfo>
			<b:Count>0</b:Count>
			<b:PageNumber>0</b:PageNumber>
			<b:PagingCookie i:nil="true" />
			<b:ReturnTotalRecordCount>false</b:ReturnTotalRecordCount>
		</b:PageInfo>
		</query>
	</RetrieveMultiple>';
	
	
	$response_xml=make_query($xml);
	
     //       echo "REQUEST: <br>".	; 
//            echo "<br>RESPONSE: <br>".$response; 
    $accountsArray = array();
    if($response_xml!=null && $response_xml!="") {

        $responsedom = new DomDocument();
        $responsedom->loadXML($response_xml);
        $entities = $responsedom->getElementsbyTagName("Entity");
        foreach($entities as $entity) {
                $reservation = array();
                $kvptypes = $entity->getElementsbyTagName("KeyValuePairOfstringanyType");
                print "<br><br>NUOVA ENTITY:<br><br>";
                foreach($kvptypes as $kvp) {
                        $key =  $kvp->getElementsbyTagName("key")->item(0)->textContent;
                        $value =  $kvp->getElementsbyTagName("value")->item(0)->textContent;
                        print "<br><br>NUOVA RIGA:<br><br>";
                        print "key=".$key." value=".$value;
                }
//                        $accountsArray[] = $reservation;
        }
    } else {
        return false;
    }
    return $accountsArray;

}

function get_appointment($city) {

	$xml='<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
      <request i:type="a:RetrieveMultipleRequest" xmlns:a="http://schemas.microsoft.com/xrm/2011/Contracts">
        <a:Parameters xmlns:b="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
          <a:KeyValuePairOfstringanyType>
            <b:key>Query</b:key>
            <b:value i:type="a:QueryExpression">
              <a:ColumnSet>
                <a:AllColumns>true</a:AllColumns>
                <a:Columns xmlns:c="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
                  <c:string>name</c:string>
                </a:Columns>	
              </a:ColumnSet>

                    <a:Criteria>
                      <a:Filters>
                        <a:FilterExpression>
                          <a:Conditions>
                            <a:ConditionExpression>
                              <a:AttributeName>subject</a:AttributeName>
                              <a:Operator>Equal</a:Operator>
                              <a:Values xmlns:b="http://schemas.microsoft.com/2003/10/Serialization/Arrays">
                                <b:anyType i:type="c:string" xmlns:c="http://www.w3.org/2001/XMLSchema">testrmr</b:anyType>
                              </a:Values>
                            </a:ConditionExpression>
                          </a:Conditions>
                        </a:FilterExpression>
                      </a:Filters>
                    </a:Criteria>


              <a:Distinct>false</a:Distinct>
              <a:EntityName>appointment</a:EntityName>
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
    </Execute>';	
	
	$response_xml=make_query($xml);
	
           echo "REQUEST: <br>".$response_xml; 
//            echo "<br>RESPONSE: <br>".$response; 
            $accountsArray = array();
            if($response_xml!=null && $response_xml!="") {

                $responsedom = new DomDocument();
                $responsedom->loadXML($response_xml);
                $entities = $responsedom->getElementsbyTagName("Entity");
                foreach($entities as $entity) {
                        $reservation = array();
                        $kvptypes = $entity->getElementsbyTagName("KeyValuePairOfstringanyType");
                        print "<br><br>NUOVA ENTITY:<br><br>";
                        foreach($kvptypes as $kvp) {
                                $key =  $kvp->getElementsbyTagName("key")->item(0)->textContent;
                                $value =  $kvp->getElementsbyTagName("value")->item(0)->textContent;					
                                print "<br><br>NUOVA RIGA:<br><br>";
                                print "key=".$key." value=".$value;
                        }
//                        $accountsArray[] = $reservation;
                }
            } else {
                return false;
            }
            return $accountsArray;

}

function create_account(){
	
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
	
	$response_xml=make_query($xml);
	
    echo "REQUEST: <br>".$response_xml; 

}

function create_appointment(){
	
    $xml='<Execute xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services">
		<request i:type="b:CreateRequest" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
      <b:Parameters xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic">
        <b:KeyValuePairOfstringanyType>
          <c:key>Target</c:key>
          <c:value i:type="b:Entity">
              <b:Attributes>
              <b:KeyValuePairOfstringanyType>
                <c:key>subject</c:key>
                <c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">test appointment</c:value>
              </b:KeyValuePairOfstringanyType>
								<b:KeyValuePairOfstringanyType>
								  <c:key>scheduledstart</c:key>
								  <c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2013-10-27T01:08:29.0439804+03:00										</c:value>
								</b:KeyValuePairOfstringanyType>
								<b:KeyValuePairOfstringanyType>
								  <c:key>scheduledend</c:key>
								  <c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2013-10-28T01:08:29.0439804+03:00										</c:value>
								</b:KeyValuePairOfstringanyType>
            </b:Attributes>
            <b:EntityState i:nil="true" />
            <b:FormattedValues />
            <b:Id>00000000-0000-0000-0000-000000000000</b:Id>
            <b:LogicalName>appointment</b:LogicalName>
            <b:RelatedEntities />
          </c:value>
        </b:KeyValuePairOfstringanyType>
      </b:Parameters>
      <b:RequestId i:nil="true" />
      <b:RequestName>Create</b:RequestName>
		</request>
	</Execute>';
	
	$response_xml=make_query($xml);
	
    echo "REQUEST: <br>".$response_xml; 

}


?>
