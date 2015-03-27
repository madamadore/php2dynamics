<?php

function testGetEquipments() {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEquips">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseEquips" aria-expanded="false" aria-controls="collapseEquips">
          <h3>All Equipments:</h3>
        </a>
      </h4>
    </div>
    <div id="collapseEquips" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEquips">
      <div class="panel-body">
          <?php
    $arrayOf = Equipment::RetrieveMultiple();
    
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

function testGetEquipment() {
    
    testEquipment( "a6cc80e3-0512-e111-933b-1cc1de086845" ); // Human resource
    testEquipment( "b4099712-571d-e311-af02-3c4a92dbd80a" ); // Bike
    testEquipment( "e45d26dc-9d1e-e211-b587-d48564531939" ); // Test Resource
     
}

function testEquipment($guid) {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEquip">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseEquip" aria-expanded="false" aria-controls="collapseEquip">
          <h3>Equipment ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapseEquip" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEquip">
      <div class="panel-body">
          <?php
    $arrayOf = Equipment::Retrieve( $guid );
    
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