<?php
function testGetBikes() {
    $conditions = array( 
        array("attribute" => "tb_framesize", "operator" => "GreaterThan", "value" => "56")
    );
    $arrayOf = Bike::RetrieveMultiple( $conditions );
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingBikes">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseBikes" aria-expanded="false" aria-controls="collapseBikes">
          <h3>All Bikes with Framesize greater than 56: (<?php echo count( $arrayOf ); ?>)</h3>
        </a>
      </h4>
    </div>
    <div id="collapseBikes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingBikes">
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
      </div></div></div>
          <?php
}

function testGetBike() {
    
    testBike( "094f370b-b488-e111-b0a5-1cc1de086845", "Bike1" );
    testBike( "5d35dba4-c5a0-e111-b6e9-1cc1de6d3b23", "Bike2" );
    testBike( "d83fdb7f-f783-e111-8d51-1cc1de6dea34", "Bike3" );
    testBike( "a440db7f-f783-e111-8d51-1cc1de6dea34", "Bike4" );
     
}

function testBike($guid, $divId = "Bike") {
    $arrayOf = Bike::Retrieve( $guid );
    ?>
    <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $divId ?>">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $divId ?>" aria-expanded="false" aria-controls="collapse<?php echo $divId ?>">
          <h3>Bike ID: <?php echo $guid ?> <?php  echo ( ! $arrayOf ) ? "FAIL" : "SUCCESS"; ?></h3>
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
      </div></div></div><?php
    
}