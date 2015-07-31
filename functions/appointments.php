<?php
require_once(dirname(__FILE__) . '/../entities/Appointment.class.php');

function testGetAppointments() {
    
    $conditions = array( 
        array("attribute" => "scheduledstart", "operator" => "GreaterThan", "value" => "2012-03-01"),
        array("attribute" => "scheduledend", "operator" => "LessThan", "value" => "2012-03-05"),
    );
    $arrayOf = Appointment::RetrieveMultiple( $conditions );
    
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingApps">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseApps" aria-expanded="false" aria-controls="collapseApps">
          <h3>All Appointments (between 01/03/2012 and 05/03/2012): (<?php echo count( $arrayOf ); ?>) </h3>
        </a>
      </h4>
    </div>
    <div id="collapseApps" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingApps">
      <div class="panel-body">
          <?php
    if ( $arrayOf ) {
            
        echo "<pre>";
        foreach ( $arrayOf as $single ) {
            var_dump( $single );
            echo "<hr />";
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
    
    testAppointment( "0538ab31-e82b-e111-80fe-1cc1de0878e1", "App1" );
    testAppointment( "8dcc124e-df45-e111-90b4-1cc1de6d3b23", "App2" );
     
}

function testAppointment($guid, $divId = "App") {
    ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $divId ?>">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $divId ?>" aria-expanded="false" aria-controls="collapse<?php echo $divId ?>">
          <h3>Appointment ID: <?php echo $guid ?> </h3>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $divId ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $divId ?>">
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