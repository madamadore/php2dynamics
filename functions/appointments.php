<?php
function testGetAppointments() {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingApps">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseApps" aria-expanded="false" aria-controls="collapseApps">
          <h3>All Appointments (between 01/03/2012 and 05/03/2012):</h3>
        </a>
      </h4>
    </div>
    <div id="collapseApps" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingApps">
      <div class="panel-body">
          <?php
    $conditions = array( 
        array("attribute" => "scheduledstart", "operator" => "GreaterThan", "value" => "2012-03-01"),
        array("attribute" => "scheduledend", "operator" => "LessThan", "value" => "2012-03-05"),
    );
    $arrayOf = Appointment::RetrieveMultiple( $conditions );
    
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

function testGetAppointment() {
    
    testAppointment( "0538ab31-e82b-e111-80fe-1cc1de0878e1" );
    testAppointment( "8dcc124e-df45-e111-90b4-1cc1de6d3b23" );
     
}

function testAppointment($guid) {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingApp">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseApp" aria-expanded="false" aria-controls="collapseApp">
          <h3>Appointment ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapseApp" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingApp">
      <div class="panel-body">
          <?php
          
    $arrayOf = Appointment::Retrieve( $guid );
    
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