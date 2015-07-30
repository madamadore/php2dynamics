<?php

function testGetProducts() {
    $conditions = array( 
        array("attribute" => "name", "operator" => "Like", "value" => "Appi%")
    );
    $arrayOf = Product::RetrieveMultiple( $conditions );
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingProds">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseProds" aria-expanded="false" aria-controls="collapseProds">
          <h3>All Products which name starts with "Appi": (<?php echo count( $arrayOf ); ?>)</h3>
        </a>
      </h4>
    </div>
    <div id="collapseProds" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingProds">
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

function testGetProduct() {
    
    testProduct( "9b2db66e-7fcc-e411-80e6-c4346bad129c", "Pro1" );
    testProduct( "8dcc124e-df45-e111-90b4-1cc1de6d3b23", "Pro2" );
     
}

function testProduct($guid, $divId = "Pro") {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $divId ?>">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $divId ?>" aria-expanded="false" aria-controls="collapse<?php echo $divId ?>">
          <h3>Product ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $divId ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $divId ?>">
      <div class="panel-body">
<?php
          
    $arrayOf = Product::Retrieve( $guid );
    
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