<?php
error_reporting(E_ALL);
global $_DEBUG_MODE;
$_DEBUG_MODE = false;

require_once "DynamicsIntegrator.class.php";
include_once "functions.php";
include_once "functions/contacts.php";
include_once "functions/bookings.php";
include_once "functions/bikemodels.php";

echo "<h1>Test started</h1>";

testGetContact();
testGetBooking();
testGetBikeModel();
testGetBikeModels();