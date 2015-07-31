<?php

require_once(dirname(__FILE__) . '/entities/Booking.class.php');
require_once(dirname(__FILE__) . '/entities/Bike.class.php');
require_once(dirname(__FILE__) . '/entities/BikeModel.class.php');
require_once(dirname(__FILE__) . '/entities/Equipment.class.php');
require_once(dirname(__FILE__) . '/entities/Price.class.php');
require_once(dirname(__FILE__) . '/entities/Product.class.php');

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