<?php

function testGetEquipments() {
    $conditions = array();
    $arrayOf = Equipment::RetrieveMultiple( $conditions );
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEquips">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEquips" aria-expanded="false" aria-controls="collapseEquips">
          <h3>All Equipments: (<?php echo count( $arrayOf ); ?>) <?php  echo ( ! $arrayOf ) ? "FAIL" : "SUCCESS"; ?></h3>
        </a>
      </h4>
    </div>
    <div id="collapseEquips" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEquips">
      <div class="panel-body">
          <?php
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
            echo "<hr />";
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
    
    testEquipment( "a6cc80e3-0512-e111-933b-1cc1de086845", "Equip1" ); // Human resource
    testEquipment( "b4099712-571d-e311-af02-3c4a92dbd80a", "Equip2" ); // Bike
    testEquipment( "e45d26dc-9d1e-e211-b587-d48564531939", "Equip3" ); // Test Resource
     
}

function testEquipment($guid, $divId = "Equip") {
    $arrayOf = Equipment::Retrieve( $guid );
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $divId ?>">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $divId ?>" aria-expanded="false" aria-controls="collapse<?php echo $divId ?>">
          <h3>Equipment ID: <?php echo $guid ?> <?php  echo ( ! $arrayOf ) ? "FAIL" : "SUCCESS"; ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $divId ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $divId ?>">
      <div class="panel-body">
          <?php
    
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