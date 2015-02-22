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

function testGetContacts($integrator) {

	echo "<br/>";
	echo "<b>TEST - GET CONTACT</b> - START<br/>";

	$contacts = $integrator->getContacts();

	echo "<b>Contatti</b><br/>";
	echo "<pre>";
	var_dump( $contacts );
	echo "</pre>";
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
	$booking->scheduledstart = "2000-03-01 14:00:00";
	$booking->scheduledend = "2000-03-01 18:00:00";
	$booking->tb_scheduledbikes = 2;
	$booking->statecode = DynamicsIntegrator::$_STATE[ "Scheduled" ];
	// $booking->statuscode = DynamicsIntegrator::$_STATUS[ "Confirmed" ];
	$booking->tb_bookingdate = "2000-01-01 20:02:20";
	$booking->tb_bookingtype = DynamicsIntegrator::$_BOOKING_TYPE[ "Direct" ];
	$booking->tb_language = DynamicsIntegrator::$_LANGUAGE[ "IT" ];
	$booking->tb_servicetype = DynamicsIntegrator::$_SERVICE_TYPE[ "bike_rental" ];
	$booking->tb_participants = 2;
	//$booking->regardingobjectid = array( "guid" => "47F0189B-1BBA-E111-B50B-D4856451DC79", "logicalName" => "contact" );    // Rimmer's GUID
	$booking->regardingobjectid = array( "guid" => "ad4c86fd-f5b8-e411-80d8-c4346bacef70", "logicalName" => "contact" );
	$booking->tb_totalamount = 40.10;
	$booking->siteid = array( "guid" => DynamicsIntegrator::$_SITE_ID, "logicalName" => "site" );
	$booking->serviceid = array( "guid" => DynamicsIntegrator::$_SERVICE_ID, "logicalName" => "service" );
	$booking->resources = array( array( "guid" => "B6099712-571D-E311-AF02-3C4A92DBD80A", "logicalName" => "equipment" ),
								 array( "guid" => "F2089712-571D-E311-AF02-3C4A92DBD80A", "logicalName" => "equipment" ) );

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

function testGetBooking($integrator) {
	$guid = "B77C3C8B-E9B8-E411-80D6-C4346BAD7228";
	$rental = $integrator->getBooking( $guid );

	$guid = "E2142DE0-40BA-E411-80D8-C4346BACEF70";
	$rental2 = $integrator->getBooking( $guid );

	$guid = "4BFEA247-40BA-E411-80D8-FC15B4280CB8";
	$rental3 = $integrator->getBooking( $guid );
}
