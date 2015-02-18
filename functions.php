<?php

function testGetContacts($integrator) {
	$integrator->getContacts();
}

function testCreateContact($integrator) {

	echo "TEST - CREATE CONTACT - START<br/>";
	$user = new stdClass();
	$user->firstname = "User";
	$user->lastname = "Test";
	$user->emailaddress = "matteo.avanzini@gmail.com";
	$user->mobilephone = "0123456789";
	$user->description = "This user is a test one. You can safely delete it of you catch him";
	$contact_id = $integrator->createContact( $user );

	echo "Contatto creato: " . $contact_id . "<br/>";
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

	echo "TEST - CREATE ACCOUNT - START<br/>";
	$user = new stdClass();
	$user->name = "Test Account";
	$contact_id = $integrator->createAccount( $user );

	echo "Account creato: " . $contact_id . "<br/>";
}
