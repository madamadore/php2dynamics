<?php
error_reporting(E_ALL);
global $_DEBUG_MODE;
$_DEBUG_MODE = true;

require_once "DynamicsIntegrator.class.php";
include_once "functions.php";

echo "Test started<br/>";

//$integrator = DynamicsIntegrator::getInstance( "website@topbike.onmicrosoft.com", "WS__2k14" );
$integrator = DynamicsIntegrator::getInstance();
// testUpdateContact($integrator);

testGetServices($integrator);
testGetContacts($integrator);
testGetBooking($integrator);
testCreateBooking($integrator);

// testDeleteContact($integrator);
// testSecurityData($integrator);
// testCreateAccount($integrator);
// testGetContacts($integrator);
// testCreateContact($integrator);
// testCheckAvaibility($integrator);
