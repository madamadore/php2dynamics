<?php
error_reporting(E_ALL);
global $_DEBUG_MODE;
$_DEBUG_MODE = true;

require_once "DynamicsIntegrator.class.php";
include_once "functions.php";

echo "<h1>Test started</h1>";


testGetContact();
testGetBooking();
// testReadXMLBooking();