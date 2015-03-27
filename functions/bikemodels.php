<?php

function testGetBikeModels() {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingBMods">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseBMods" aria-expanded="false" aria-controls="collapseBMods">
          <h3>All Bike Models:</h3>
        </a>
      </h4>
    </div>
    <div id="collapseBMods" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingBMods">
      <div class="panel-body">
          <?php
    $arrayOf = BikeModel::RetrieveMultiple();
    
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

function testGetBikeModel() {
    
    testBikeModel( "907671dc-cb2d-e311-848b-1cc1deeaca61" );
     
}

function testBikeModel($guid) {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingBMod">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseBMod" aria-expanded="false" aria-controls="collapseBMod">
          <h3>Bike Model ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapseBMod" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingBMod">
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