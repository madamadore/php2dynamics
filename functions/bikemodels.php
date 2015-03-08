<?php

function testGetBikeModels() {
    $arrayOf = BikeModel::RetrieveMultiple();
    
    echo "<h3>All Bike Models:</h3><br />";
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
        }
        echo "</pre>";
        
    }
}

function testGetBikeModel() {
    
    testBikeModel( "907671dc-cb2d-e311-848b-1cc1deeaca61" );
     
}

function testBikeModel($guid) {
    
    $arrayOf = BikeModel::Retrieve( $guid );

    echo "<h3>Bike Model ID:</h3>" . $guid . "<br />";
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
        }
        echo "</pre>";
        
    }   
    
}