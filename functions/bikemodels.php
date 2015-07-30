<?php

function testGetBikeModels() {
    $arrayOf = BikeModel::RetrieveMultiple();
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingBMods">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseBMods" aria-expanded="false" aria-controls="collapseBMods">
          <h3>All Bike Models: (<?php echo count( $arrayOf ); ?>)</h3>
        </a>
      </h4>
    </div>
    <div id="collapseBMods" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingBMods">
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

function testGetBikeModel() {
    
    testBikeModel( "907671dc-cb2d-e311-848b-1cc1deeaca61", "BMod1" );
     
}

function testBikeModel($guid, $divId = "BMod") {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $divId ?>">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $divId ?>" aria-expanded="false" aria-controls="collapse<?php echo $divId ?>">
          <h3>Bike Model ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $divId ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $divId ?>">
      <div class="panel-body">
          <?php
    $arrayOf = BikeModel::Retrieve( $guid );
    
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