<?php

Codeception\Specify\Config::setIgnoredProperties(['user']);

class AppointmentTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {        
    }

    protected function _after()
    {
    }
    
    public function testRetrieveSingleAppointment() {
        $obj = new Appointment();
        $appointment = $obj->RetrieveSingle( "126AA41D-CD2F-E111-8D45-1CC1DE6DEA34" );
        $this->assertTrue( is_object( $appointment ), "Single appointment retrived" );
        $this->assertEquals( $appointment->subject, "10 - EN City Center" );
    }
    
    public function testRetrieveMultipleAppointments() {
        $conditions = array( 
            array("attribute" => "scheduledstart", "operator" => "GreaterThan", "value" => "2012-08-10T09:00:00Z"),
            array("attribute" => "scheduledend", "operator" => "LessThan", "value" => "2012-08-16T23:00:00Z")
        );
        $obj = new Appointment();
        $arrayOf = $obj->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple objects" );
    }
    
}