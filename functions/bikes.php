<?php
function testGetBikes() {
    $conditions = array( 
        array("attribute" => "tb_framesize", "operator" => "GreaterThan", "value" => "54")
    );
    $arrayOf = Bike::RetrieveMultiple( $conditions );
    
    echo "<h3>All Bikes:</h3><br />";
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
        }
        echo "</pre>";
        
    }
}

function testGetBike() {
    
    testBike( "" );
     
}

function testBike($guid) {
    
    $arrayOf = Bike::Retrieve( $guid );

    echo "<h3>Bike ID: " . $guid . "</h3>";
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
        }
        echo "</pre>";
        
    }   
    
}