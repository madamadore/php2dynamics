<?php

function testDeleteContact($integrator) {
	echo "<b>TEST - DELETE CONTACT</b>";
	$integrator->deleteContact( "1e1aa07f-f4b8-e411-80d8-c4346bacef70" );
}

function testUpdateContact($integrator) {

	echo "<b>TEST - UPDATE CONTACT</b> - START<br/>";
	$user = new Contact("Matteo Avanzini");
	$user->firstname = "Matteo";
	$user->lastname = "Avanzini";
	$user->emailaddress1 = "lomion@tiscali.it";
	$user->mobilephone = "0123456789";
	$user->description = "This user is a test one. You can safely delete it of you catch him";

	$integrator->updateContact( $user, "ad4c86fd-f5b8-e411-80d8-c4346bacef70" );
}

function testCreateContact($integrator) {

	echo "<b>TEST - CREATE CONTACT</b> - START<br/>";
	$user = new Contact("User Test");
	$user->firstname = "User";
	$user->lastname = "Test";
	$user->emailaddress1 = "matteo.avanzini@gmail.com";
	$user->mobilephone = "0123456789";
	$user->description = "This user is a test one. You can safely delete it of you catch him";

	$contact_id = $integrator->createContact( $user );

	echo "Contatto creato: " . $contact_id . "<br/>";
}

function testGetContact() {
    
    testContact( "47f0189b-1bba-e111-b50b-d4856451dc79" );  // Rimmer Lankaster
    testContact( "1e1aa07f-f4b8-e411-80d8-c4346bacef70" );  // has to be void
     
}

function testContact($guid) {
    
    $arrayOfContacts = Contact::Retrieve( $guid );

    echo "<h3>Booking ID:</h3>" . $guid . "<br />";
    
    if ( $arrayOfContacts ) {
            
        echo "<pre>";
        foreach ( $arrayOfContacts as $contact ) {
            var_dump( $contact );
        }
        echo "</pre>";
        
    }
    
}

function testSecurityData($integrator) {
	echo "Dynamic integrator created<br/>";

	$securityData = $integrator::getSecurityData();

	if ( null === $securityData ) {
		die("FAIL: security data not present");
	}
	echo "Security data check: <br/>";
	echo "<pre>";
	echo var_dump( $securityData );
	echo "</pre>";
}

function testCreateAccount($integrator) {

	echo "<br/>";
	echo "<b>TEST - CREATE ACCOUNT</b><br/>";

	$account = new Account("Test Account");
	$contact_id = $integrator->createAccount( $account );

	echo "Account creato: " . $contact_id . "<br/>";
}

function testCreateBooking($integrator) {
	echo "<b>TEST - CREATE BOOKING</b>";

	$booking = new Booking("Bike Rental Test");
	$booking->scheduledstart = "2000-03-01 13:00:00";
	$booking->scheduledend = "2000-03-01 17:00:00";
	$booking->tb_scheduledbikes = 2;
	$booking->tb_bookingdate = "2000-01-01 20:02:20";
	$booking->tb_bookingtype = array( "value" =>DynamicsIntegrator::$_BOOKING_TYPE[ "Web" ], "type"=>"string" );
	$booking->tb_language = array( "value" =>DynamicsIntegrator::$_LANGUAGE[ "IT" ], "type"=>"string" );
	$booking->tb_servicetype = array( "value" =>DynamicsIntegrator::$_SERVICE_TYPE[ "bike_rental" ], "type"=>"string" );
	$booking->tb_participants = 2;
	$booking->regardingobjectid = array( "guid" => "ad4c86fd-f5b8-e411-80d8-c4346bacef70", "logicalName" => "contact" );
	$booking->tb_totalamount = 40.10;
	$booking->siteid = array( "guid" => DynamicsIntegrator::$_SITE_ID, "logicalName" => "site" );
	$booking->serviceid = array( "guid" => DynamicsIntegrator::$_SERVICE_ID[ "Rental" ], "logicalName" => "service" );
	$booking->resources = array( array( "guid" => "B6099712-571D-E311-AF02-3C4A92DBD80A", "logicalName" => "equipment" ),
								 array( "guid" => "F2089712-571D-E311-AF02-3C4A92DBD80A", "logicalName" => "equipment" ) );
	// $booking_id = $integrator->createBooking( $booking );

	$booking = new Booking("Private Tour Test");
	$booking->scheduledstart = "2000-03-01 08:00:00";
	$booking->scheduledend = "2000-03-01 09:00:00";
	$booking->tb_scheduledbikes = 1;
	$booking->tb_bookingdate = "2000-01-01 20:02:20";
	$booking->tb_bookingtype = DynamicsIntegrator::$_BOOKING_TYPE[ "Web" ];
	$booking->tb_language = array( "value" =>DynamicsIntegrator::$_LANGUAGE[ "DE" ], "type"=>"string" );
	$booking->tb_servicetype = array( "value" =>DynamicsIntegrator::$_SERVICE_TYPE[ "private_tour" ], "type"=>"string" );
	$booking->tb_participants = 2;
	$booking->regardingobjectid = array( "guid" => "ad4c86fd-f5b8-e411-80d8-c4346bacef70", "logicalName" => "contact" );
	$booking->tb_totalamount = 40.10;
	$booking->siteid = array( "guid" => DynamicsIntegrator::$_SITE_ID, "logicalName" => "site" );
	$booking->serviceid = array( "guid" => DynamicsIntegrator::$_SERVICE_ID[ "Tour" ], "logicalName" => "service" );
	$booking->resources = array( array( "guid" => "B6099712-571D-E311-AF02-3C4A92DBD80A", "logicalName" => "equipment" ) );
	// $booking_id = $integrator->createBooking( $booking );
	$booking->Create();

	$booking = new Booking("Scheduled Tour Test");
	$booking->scheduledstart = "2000-03-01 10:00:00";
	$booking->scheduledend = "2000-03-01 12:00:00";
	$booking->tb_scheduledbikes = 1;
	$booking->tb_bookingdate = "2000-01-01 20:02:20";
	$booking->tb_bookingtype = array( "value" =>DynamicsIntegrator::$_BOOKING_TYPE[ "Web" ], "type"=>"string" );
	$booking->tb_language = array( "value" =>DynamicsIntegrator::$_LANGUAGE[ "NL" ], "type"=>"string" );
	$booking->tb_servicetype = array( "value" =>DynamicsIntegrator::$_SERVICE_TYPE[ "scheduled_tour" ], "type"=>"string" );
	$booking->tb_participants = 1;
	$booking->tb_tourid = array( "guid" => "813a65f3-7acf-e211-b5f3-d48564531939", "logicalName" => "appointment" );
	$booking->regardingobjectid = array( "guid" => "ad4c86fd-f5b8-e411-80d8-c4346bacef70", "logicalName" => "contact" );
	$booking->tb_totalamount = 40.10;
	$booking->siteid = array( "guid" => DynamicsIntegrator::$_SITE_ID, "logicalName" => "site" );
	$booking->serviceid = array( "guid" => DynamicsIntegrator::$_SERVICE_ID[ "Tour" ], "logicalName" => "service" );
	$booking->resources = array( array( "guid" => "B6099712-571D-E311-AF02-3C4A92DBD80A", "logicalName" => "equipment" ) );
	$booking_id = $integrator->createBooking( $booking );
}

function testGetServices($integrator) {

	echo "<br/>";
	echo "<b>TEST - GET SERVICES</b> - START<br/>";

	$contacts = $integrator->getServices();

	echo "<b>Services</b><br/>";
	echo "<pre>";
	var_dump( $contacts );
	echo "</pre>";
}

function testCheckAvaibility($integrator) {

	echo "<br />";
	echo "<b>TEST CHECK AVAIBILITY</b><br/>";

	$resources = array( "B6099712-571D-E311-AF02-3C4A92DBD80A",
						"FE089712-571D-E311-AF02-3C4A92DBD80A",
						"F2089712-571D-E311-AF02-3C4A92DBD80A" );

	$start_date = "2000-02-01 10:00:00";
	$end_date = "2000-02-02 11:00:00";
	$response = $integrator->checkAvailability($resources, $start_date, $end_date, "0 hour");
	echo "<pre>";
	echo var_dump( $response );
	echo "</pre>";

	echo "<br/>";

	$start_date = "2000-02-01 12:00:00";
	$end_date = "2000-02-06 12:00:00";
	$response = $integrator->checkAvailability( $resources, $start_date, $end_date );
	echo "<pre>";
	echo var_dump( $response );
	echo "</pre>";

	$start_date = "2000-03-01 14:00:00";
	$end_date = "2000-03-01 15:00:00";
	$response = $integrator->checkAvailability( $resources, $start_date, $end_date, "0 hour" );
	echo "<pre>";
	echo var_dump( $response );
	echo "</pre>";

	$start_date = "2000-02-01 09:00:00";
	$end_date = "2000-02-01 09:30:00";
	$response = $integrator->checkAvailability( $resources, $start_date, $end_date, "0 hour" );
	echo "<pre>";
	echo var_dump( $response );
	echo "</pre>";

	$start_date = "2000-02-08 09:00:00";
	$end_date = "2000-02-15 18:00:00";
	$response = $integrator->checkAvailability( $resources, $start_date, $end_date, "0 hour" );
	echo "<pre>";
	echo var_dump( $response );
	echo "</pre>";
}

function testGetBooking() {
    
    testBooking( "B77C3C8B-E9B8-E411-80D6-C4346BAD7228" );
    testBooking( "E2142DE0-40BA-E411-80D8-C4346BACEF70" );
    testBooking( "4BFEA247-40BA-E411-80D8-FC15B4280CB8" );
     
}

function testBooking($guid) {
    
    $arrayOfBooking = Booking::Retrieve($guid);

    echo "<h3>Booking ID:</h3>" . $guid . "<br />";
    
    if ( $arrayOfBooking ) {
            
        echo "<pre>";
        foreach ( $arrayOfBooking as $booking ) {
            var_dump( $booking );
        }
        echo "</pre>";
        
    }
    
}

function testReadXMLBooking() {
    
    $response = '<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing" xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><s:Header><a:Action s:mustUnderstand="1">http://schemas.microsoft.com/xrm/2011/Contracts/Services/IOrganizationService/ExecuteResponse</a:Action><a:RelatesTo>urn:uuid:e26dce6c-fcaf-4810-bd8f-eb57981cecd7</a:RelatesTo><ActivityId CorrelationId="bc2b5f4b-ce7d-4ff7-98f3-01119e27e5fd" xmlns="http://schemas.microsoft.com/2004/09/ServiceModel/Diagnostics">00000000-0000-0000-0000-000000000000</ActivityId><o:Security s:mustUnderstand="1" xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"><u:Timestamp u:Id="_0"><u:Created>2015-03-08T02:05:44.659Z</u:Created><u:Expires>2015-03-08T02:10:44.659Z</u:Expires></u:Timestamp></o:Security></s:Header><s:Body><ExecuteResponse xmlns="http://schemas.microsoft.com/xrm/2011/Contracts/Services"><ExecuteResult i:type="b:RetrieveMultipleResponse" xmlns:b="http://schemas.microsoft.com/xrm/2011/Contracts" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><b:ResponseName>RetrieveMultiple</b:ResponseName><b:Results xmlns:c="http://schemas.datacontract.org/2004/07/System.Collections.Generic"><b:KeyValuePairOfstringanyType><c:key>EntityCollection</c:key><c:value i:type="b:EntityCollection"><b:Entities><b:Entity><b:Attributes><b:KeyValuePairOfstringanyType><c:key>prioritycode</c:key><c:value i:type="b:OptionSetValue"><b:Value>2</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>isbilled</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_topbikerevenue</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>isworkflowcreated</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>activityid</c:key><c:value i:type="d:guid" xmlns:d="http://schemas.microsoft.com/2003/10/Serialization/">b77c3c8b-e9b8-e411-80d6-c4346bad7228</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_deposit</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>exchangerate</c:key><c:value i:type="d:decimal" xmlns:d="http://www.w3.org/2001/XMLSchema">1.0000000000</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_notificationsent</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_openamount</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_overridefinancials</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_freetshirt</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>siteid</c:key><c:value i:type="b:EntityReference"><b:Id>7809f317-e4b8-e311-8b9a-d89d67638ee8</b:Id><b:LogicalName>site</b:LogicalName><b:Name>Labicana</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>owningbusinessunit</c:key><c:value i:type="b:EntityReference"><b:Id>e1fc466b-2100-e111-863f-1cc1de0878e1</b:Id><b:LogicalName>businessunit</b:LogicalName><b:Name i:nil="true"/></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>owninguser</c:key><c:value i:type="b:EntityReference"><b:Id>adb45933-85fd-4489-917f-3cd1131cb71b</b:Id><b:LogicalName>systemuser</b:LogicalName><b:Name i:nil="true"/></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_scheduledbikes</c:key><c:value i:type="d:double" xmlns:d="http://www.w3.org/2001/XMLSchema">2</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>regardingobjectid</c:key><c:value i:type="b:EntityReference"><b:Id>47f0189b-1bba-e111-b50b-d4856451dc79</b:Id><b:LogicalName>contact</b:LogicalName><b:Name>Rimmer Lankester</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_isquickcreated</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>ownerid</c:key><c:value i:type="b:EntityReference"><b:Id>adb45933-85fd-4489-917f-3cd1131cb71b</b:Id><b:LogicalName>systemuser</b:LogicalName><b:Name>System Administrator</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>scheduledend</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2000-02-05T11:00:00Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_transport</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_totalamount_base</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>createdon</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2015-02-20T10:16:34Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_invoicerequested</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>actualstart</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2015-02-20T10:16:33Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_deposit_base</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>serviceid</c:key><c:value i:type="b:EntityReference"><b:Id>74356ab9-1244-e111-90b4-1cc1de6d3b23</b:Id><b:LogicalName>service</b:LogicalName><b:Name>Rental 2</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_openamount_base</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>timezoneruleversionnumber</c:key><c:value i:type="d:int" xmlns:d="http://www.w3.org/2001/XMLSchema">0</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_bookingdate</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2015-02-19T23:00:00Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>ismapiprivate</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>statuscode</c:key><c:value i:type="b:OptionSetValue"><b:Value>4</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_questionnaire</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">true</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_bookingtype</c:key><c:value i:type="b:OptionSetValue"><b:Value>108600003</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_language</c:key><c:value i:type="b:OptionSetValue"><b:Value>108600000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>isregularactivity</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">true</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_isreopened</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>scheduledstart</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2000-02-01T10:30:00Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_productid</c:key><c:value i:type="b:EntityReference"><b:Id>11447825-ae42-e111-90b4-1cc1de6d3b23</b:Id><b:LogicalName>product</b:LogicalName><b:Name>Rental</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_availability_offline</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>modifiedon</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2015-02-20T10:16:41Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>transactioncurrencyid</c:key><c:value i:type="b:EntityReference"><b:Id>b447452e-420c-e111-bf37-1cc1de0878e1</b:Id><b:LogicalName>transactioncurrency</b:LogicalName><b:Name>euro</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>subject</c:key><c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">TEST RENTAL FOR INTEGRATION</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_participants</c:key><c:value i:type="d:int" xmlns:d="http://www.w3.org/2001/XMLSchema">2</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>isalldayevent</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>modifiedby</c:key><c:value i:type="b:EntityReference"><b:Id>adb45933-85fd-4489-917f-3cd1131cb71b</b:Id><b:LogicalName>systemuser</b:LogicalName><b:Name>System Administrator</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>createdby</c:key><c:value i:type="b:EntityReference"><b:Id>adb45933-85fd-4489-917f-3cd1131cb71b</b:Id><b:LogicalName>systemuser</b:LogicalName><b:Name>System Administrator</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_topbikerevenue_base</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_totalamount</c:key><c:value i:type="b:Money"><b:Value>0.0000</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>tb_servicetype</c:key><c:value i:type="b:OptionSetValue"><b:Value>108600002</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>statecode</c:key><c:value i:type="b:OptionSetValue"><b:Value>3</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>scheduleddurationminutes</c:key><c:value i:type="d:int" xmlns:d="http://www.w3.org/2001/XMLSchema">5790</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>activitytypecode</c:key><c:value i:type="d:string" xmlns:d="http://www.w3.org/2001/XMLSchema">serviceappointment</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>customers</c:key><c:value i:type="b:EntityCollection"><b:Entities/><b:EntityName>activityparty</b:EntityName><b:MinActiveRowVersion>-1</b:MinActiveRowVersion><b:MoreRecords>false</b:MoreRecords><b:PagingCookie i:nil="true"/><b:TotalRecordCount>-1</b:TotalRecordCount><b:TotalRecordCountLimitExceeded>false</b:TotalRecordCountLimitExceeded></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>resources</c:key><c:value i:type="b:EntityCollection"><b:Entities><b:Entity><b:Attributes><b:KeyValuePairOfstringanyType><c:key>ispartydeleted</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>activityid</c:key><c:value i:type="b:EntityReference"><b:Id>b77c3c8b-e9b8-e411-80d6-c4346bad7228</b:Id><b:LogicalName>activitypointer</b:LogicalName><b:Name i:nil="true"/></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>resourcespecid</c:key><c:value i:type="b:EntityReference"><b:Id>73356ab9-1244-e111-90b4-1cc1de6d3b23</b:Id><b:LogicalName>resourcespec</b:LogicalName><b:Name>Selection Rule</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>participationtypemask</c:key><c:value i:type="b:OptionSetValue"><b:Value>10</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>effort</c:key><c:value i:type="d:double" xmlns:d="http://www.w3.org/2001/XMLSchema">1</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>scheduledend</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2000-02-05T11:00:00Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>ownerid</c:key><c:value i:type="b:EntityReference"><b:Id>adb45933-85fd-4489-917f-3cd1131cb71b</b:Id><b:LogicalName>systemuser</b:LogicalName><b:Name i:nil="true"/></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>partyid</c:key><c:value i:type="b:EntityReference"><b:Id>f2089712-571d-e311-af02-3c4a92dbd80a</b:Id><b:LogicalName>equipment</b:LogicalName><b:Name>001 (54 ARB) Dolce 6.9</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>activitypartyid</c:key><c:value i:type="d:guid" xmlns:d="http://schemas.microsoft.com/2003/10/Serialization/">b97c3c8b-e9b8-e411-80d6-c4346bad7228</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>instancetypecode</c:key><c:value i:type="b:OptionSetValue"><b:Value>0</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>scheduledstart</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2000-02-01T10:30:00Z</c:value></b:KeyValuePairOfstringanyType></b:Attributes><b:EntityState i:nil="true"/><b:FormattedValues><b:KeyValuePairOfstringstring><c:key>ispartydeleted</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>participationtypemask</c:key><c:value>Resource</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>effort</c:key><c:value>1,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>scheduledend</c:key><c:value>5-2-2000</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>instancetypecode</c:key><c:value>Not Recurring</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>scheduledstart</c:key><c:value>1-2-2000</c:value></b:KeyValuePairOfstringstring></b:FormattedValues><b:Id>b97c3c8b-e9b8-e411-80d6-c4346bad7228</b:Id><b:LogicalName>activityparty</b:LogicalName><b:RelatedEntities/></b:Entity><b:Entity><b:Attributes><b:KeyValuePairOfstringanyType><c:key>ispartydeleted</c:key><c:value i:type="d:boolean" xmlns:d="http://www.w3.org/2001/XMLSchema">false</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>activityid</c:key><c:value i:type="b:EntityReference"><b:Id>b77c3c8b-e9b8-e411-80d6-c4346bad7228</b:Id><b:LogicalName>activitypointer</b:LogicalName><b:Name i:nil="true"/></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>resourcespecid</c:key><c:value i:type="b:EntityReference"><b:Id>73356ab9-1244-e111-90b4-1cc1de6d3b23</b:Id><b:LogicalName>resourcespec</b:LogicalName><b:Name>Selection Rule</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>participationtypemask</c:key><c:value i:type="b:OptionSetValue"><b:Value>10</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>effort</c:key><c:value i:type="d:double" xmlns:d="http://www.w3.org/2001/XMLSchema">1</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>scheduledend</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2000-02-05T11:00:00Z</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>ownerid</c:key><c:value i:type="b:EntityReference"><b:Id>adb45933-85fd-4489-917f-3cd1131cb71b</b:Id><b:LogicalName>systemuser</b:LogicalName><b:Name i:nil="true"/></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>partyid</c:key><c:value i:type="b:EntityReference"><b:Id>b6099712-571d-e311-af02-3c4a92dbd80a</b:Id><b:LogicalName>equipment</b:LogicalName><b:Name>142 (58 ARB) Race Actinum 6000</b:Name></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>activitypartyid</c:key><c:value i:type="d:guid" xmlns:d="http://schemas.microsoft.com/2003/10/Serialization/">ba7c3c8b-e9b8-e411-80d6-c4346bad7228</c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>instancetypecode</c:key><c:value i:type="b:OptionSetValue"><b:Value>0</b:Value></c:value></b:KeyValuePairOfstringanyType><b:KeyValuePairOfstringanyType><c:key>scheduledstart</c:key><c:value i:type="d:dateTime" xmlns:d="http://www.w3.org/2001/XMLSchema">2000-02-01T10:30:00Z</c:value></b:KeyValuePairOfstringanyType></b:Attributes><b:EntityState i:nil="true"/><b:FormattedValues><b:KeyValuePairOfstringstring><c:key>ispartydeleted</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>participationtypemask</c:key><c:value>Resource</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>effort</c:key><c:value>1,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>scheduledend</c:key><c:value>5-2-2000</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>instancetypecode</c:key><c:value>Not Recurring</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>scheduledstart</c:key><c:value>1-2-2000</c:value></b:KeyValuePairOfstringstring></b:FormattedValues><b:Id>ba7c3c8b-e9b8-e411-80d6-c4346bad7228</b:Id><b:LogicalName>activityparty</b:LogicalName><b:RelatedEntities/></b:Entity></b:Entities><b:EntityName>activityparty</b:EntityName><b:MinActiveRowVersion>-1</b:MinActiveRowVersion><b:MoreRecords>false</b:MoreRecords><b:PagingCookie i:nil="true"/><b:TotalRecordCount>-1</b:TotalRecordCount><b:TotalRecordCountLimitExceeded>false</b:TotalRecordCountLimitExceeded></c:value></b:KeyValuePairOfstringanyType></b:Attributes><b:EntityState i:nil="true"/><b:FormattedValues><b:KeyValuePairOfstringstring><c:key>prioritycode</c:key><c:value>High</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>isbilled</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_topbikerevenue</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>isworkflowcreated</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_deposit</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>exchangerate</c:key><c:value>1,0000000000</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_notificationsent</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_openamount</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_overridefinancials</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_freetshirt</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_scheduledbikes</c:key><c:value>2</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_isquickcreated</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>scheduledend</c:key><c:value>5-2-2000 12:00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_transport</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_totalamount_base</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>createdon</c:key><c:value>20-2-2015 11:16</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_invoicerequested</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>actualstart</c:key><c:value>20-2-2015 11:16</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_deposit_base</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_openamount_base</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>timezoneruleversionnumber</c:key><c:value>0</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_bookingdate</c:key><c:value>20-2-2015</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>ismapiprivate</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>statuscode</c:key><c:value>Confirmed</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_questionnaire</c:key><c:value>Yes</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_bookingtype</c:key><c:value>Direct</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_language</c:key><c:value>EN</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>isregularactivity</c:key><c:value>Yes</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_isreopened</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>scheduledstart</c:key><c:value>1-2-2000 11:30</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_availability_offline</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>modifiedon</c:key><c:value>20-2-2015 11:16</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_participants</c:key><c:value>2</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>isalldayevent</c:key><c:value>No</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_topbikerevenue_base</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_totalamount</c:key><c:value>€‎ 0,00</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>tb_servicetype</c:key><c:value>Rental</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>statecode</c:key><c:value>Scheduled</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>scheduleddurationminutes</c:key><c:value>5.790</c:value></b:KeyValuePairOfstringstring><b:KeyValuePairOfstringstring><c:key>activitytypecode</c:key><c:value>Booking</c:value></b:KeyValuePairOfstringstring></b:FormattedValues><b:Id>b77c3c8b-e9b8-e411-80d6-c4346bad7228</b:Id><b:LogicalName>serviceappointment</b:LogicalName><b:RelatedEntities/></b:Entity></b:Entities><b:EntityName>serviceappointment</b:EntityName><b:MinActiveRowVersion>-1</b:MinActiveRowVersion><b:MoreRecords>false</b:MoreRecords><b:PagingCookie>&lt;cookie page="1"&gt;&lt;activityid last="{B77C3C8B-E9B8-E411-80D6-C4346BAD7228}" first="{B77C3C8B-E9B8-E411-80D6-C4346BAD7228}" /&gt;&lt;/cookie&gt;</b:PagingCookie><b:TotalRecordCount>-1</b:TotalRecordCount><b:TotalRecordCountLimitExceeded>false</b:TotalRecordCountLimitExceeded></c:value></b:KeyValuePairOfstringanyType></b:Results></ExecuteResult></ExecuteResponse></s:Body></s:Envelope>';
    
    $xmlReader = new CrmXmlReader();
    $entities = $xmlReader->getEntities( $response );

    $bookings = array();
    foreach ($entities as $entity) {
        echo "<pre>";
        var_dump( $entity );
        var_dump( property_exists( $entity, "ispartydeleted" ) );
        echo "</pre>";
    
        if ( property_exists( $entity, "subject" ) ) {
            $bookings[] = $entity;
        }
    }
    
    echo "<pre>";
    var_dump( $bookings );
    echo "</pre>";

}