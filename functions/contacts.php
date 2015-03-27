<?php

function testGetContacts() {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingCons">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseCons" aria-expanded="false" aria-controls="collapseCons">
          <h3>All Contacts starting with 'Ma':</h3>
        </a>
      </h4>
    </div>
    <div id="collapseCons" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingCons">
      <div class="panel-body">
          <?php
    $conditions = array( 
        array("attribute" => "firstname", "operator" => "Like", "value" => "Ma%")
    );
    
    $arrayOf = Contact::RetrieveMultiple( $conditions );
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
        }
        echo "</pre>";
        
    }
    ?>
      </div>
    </div>
  </div>
    <?php
}

function testGetContact() {
    
    testContact( "47f0189b-1bba-e111-b50b-d4856451dc79" );  // Rimmer Lankaster
    testContact( "1e1aa07f-f4b8-e411-80d8-c4346bacef70" );  // has to be void
     
}

function testContact($guid) {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingCon">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseCon" aria-expanded="false" aria-controls="collapseCon">
          <h3>Contact ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapseCon" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingCon">
      <div class="panel-body">
          <?php
    $arrayOfContacts = Contact::Retrieve( $guid );
    
    if ( $arrayOfContacts ) {
            
        echo "<pre>";
        foreach ( $arrayOfContacts as $contact ) {
            var_dump( $contact );
        }
        echo "</pre>";
        
    }  
    ?>
      </div>
    </div>
  </div>
    <?php
}

// **********

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
