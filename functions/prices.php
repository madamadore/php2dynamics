<?php

function testGetPrices() {
    $conditions = array("attribute" => "tb_price", "operator" => "GreaterThan", "value" => "11.50");
    $arrayOf = Price::RetrieveMultiple( $conditions );
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingPrices">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePrices" aria-expanded="false" aria-controls="collapsePrices">
          <h3>All Prices > 11.00: (<?php echo count( $arrayOf ); ?>) <?php echo ( ! $arrayOf ) ? "FAIL" : "SUCCESS"; ?></h3>
        </a>
      </h4>
    </div>
    <div id="collapsePrices" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingPrices">
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

function testGetPrice() {
    
    testPrice( "9b2db66e-7fcc-e411-80e6-c4346bad129c", "Pri1" );
    testPrice( "7f2db66e-7fcc-e411-80e6-c4346bad129c", "Pri2" );
     
}

function testPrice($guid, $divId = "Pri") {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $divId ?>">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $divId ?>" aria-expanded="false" aria-controls="collapse<?php echo $divId ?>">
          <h3>Price ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $divId ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $divId ?>">
      <div class="panel-body">
<?php
          
    $arrayOf = Price::Retrieve( $guid );
    
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