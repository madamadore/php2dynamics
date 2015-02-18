<?php
error_reporting(E_ALL);
global $_DEBUG_MODE;
$_DEBUG_MODE = true;

require_once "DynamicsIntegrator.class.php";
include_once "functions.php";

echo "Test started<br/>";

$integrator = DynamicsIntegrator::getInstance( "website@topbike.onmicrosoft.com", "WS__2k14" );
echo "Dynamic integrator created<br/>";

$securityData = $integrator::getSecurityData();

if ( null === $securityData ) {
	die("FAIL: security data not present");
}
echo "Security data check: <br/>";
echo "<pre>";
echo var_dump( $securityData );
echo "</pre>";

testGetContacts($integrator);
testCreateContact($integrator);


