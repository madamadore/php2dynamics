<?php

function testGetContacts($integrator) {
	$integrator->getContacts();
}

function testCreateContact($integrator) {

	$user = new stdClass();
	$user->firstname = "User";
	$user->lastname = "Test";
	$user->emailaddress = "matteo.avanzini@gmail.com";
	$user->mobilephone = "0123456789";
	$user->description = "This user is a test one. You can safely delete it of you catch him";
	$contact_id = $integrator->createContact( $user );

	echo "Contatto creato: " . $contact_id . "<br/>";
}
