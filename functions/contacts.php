<?php

require_once(dirname(__FILE__) . '/../entities/Contact.class.php');

function testGetContacts() {
    $conditions = array( 
        array("attribute" => "firstname", "operator" => "Like", "value" => "Ma%")
    );
    
    $arrayOf = Contact::RetrieveMultiple( $conditions );
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingCons">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseCons" aria-expanded="false" aria-controls="collapseCons">
          <h3>All Contacts starting with 'Ma': (<?php echo count( $arrayOf ); ?>) <?php  echo ( ! $arrayOf ) ? "FAIL" : "SUCCESS"; ?></h3>
        </a>
      </h4>
    </div>
    <div id="collapseCons" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingCons">
      <div class="panel-body">
          <?php

    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
            echo "<hr/>";
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
    
    testContact( "47f0189b-1bba-e111-b50b-d4856451dc79", "Con1" );  // Rimmer Lankaster
    testContact( "1e1aa07f-f4b8-e411-80d8-c4346bacef70", "Con2" );  // has to be void
     
}

function testContact($guid, $divId = "Con") {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $divId ?>">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $divId ?>" aria-expanded="false" aria-controls="collapse<?php echo $divId ?>">
          <h3>Contact ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $divId ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $divId ?>">
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

function testDeleteContact() {
	echo "<b>TEST - DELETE CONTACT</b>";
	Contact::Delete( "1e1aa07f-f4b8-e411-80d8-c4346bacef70" );
}

function testUpdateContact() {

	echo "<b>TEST - UPDATE CONTACT</b> - START<br/>";
	$user = new Contact("Matteo Avanzini");
	$user->firstname = "Matteo";
	$user->lastname = "Avanzini";
	$user->emailaddress1 = "lomion@tiscali.it";
	$user->mobilephone = "0123456789";
	$user->description = "This user is a test one. You can safely delete it of you catch him";

	$user->Update( "ad4c86fd-f5b8-e411-80d8-c4346bacef70" );
}

function testCreateContact() {

	echo "<b>TEST - CREATE CONTACT</b> - START<br/>";
	$user = new Contact("User Test");
	$user->firstname = "User";
	$user->lastname = "Test";
	$user->emailaddress1 = "matteo.avanzini@gmail.com";
	$user->mobilephone = "0123456789";
	$user->description = "This user is a test one. You can safely delete it if you catch him";

        $response = $user->Create();
        echo "<pre>";
        var_dump($response);
        echo "</pre>";
        
        $responsedom = new DomDocument();
        $responsedom->loadXML( $response );
        $nodes = $responsedom->getElementsbyTagName("keyvaluepairofstringanytype");
        $created_id = false;
        foreach ($nodes as $node) {
            $created_id =  $node->getElementsbyTagName("value")->item(0)->textContent;
        }

	echo "Contatto creato: " . $created_id . "<br/>";
}
