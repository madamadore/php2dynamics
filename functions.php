<?php

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