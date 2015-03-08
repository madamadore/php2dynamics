<?php

function testGetAppointments() {
    
    testAppointment( "" );
    testAppointment( "" );
     
}

function testAppointment($guid) {
    
    $arrayOf = Appointment::Retrieve( $guid );

    echo "<h3>Appointment ID:</h3>" . $guid . "<br />";
    
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
        }
        echo "</pre>";
        
    }   
    
}