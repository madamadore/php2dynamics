<?php

function testGetBikes() {
    
    testBike( "" );
     
}

function testBike($guid) {
    
    $arrayOf = Bike::Retrieve( $guid );

    echo "<h3>Bike ID:</h3>" . $guid . "<br />";
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
        }
        echo "</pre>";
        
    }   
    
}