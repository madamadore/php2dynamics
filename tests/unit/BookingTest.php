<?php

Codeception\Specify\Config::setIgnoredProperties(['user']);

class BookingTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $object;
    
    protected function _before()
    {        
        $object = new Booking("");
    }

    protected function _after()
    {
    }
    
    public function testCrudBooking() {
        
    }
    
    public function testRetrieveSingleBooking() {
        $obj = new Booking();
        $appointment = $obj->RetrieveSingle( "98CB9C02-D83D-E511-8118-C4346BAD5034" );
        $this->assertTrue( is_object( $appointment ), "Single booking retrived" );
        $this->assertEquals( $appointment->subject, "EN Panoramic Tour" );
        $this->assertEquals( $appointment->tb_participants, 5 );
    }
    
    public function testRetrieveMultipleBookings() {
        $conditions = array( 
            array("attribute" => "scheduledstart", "operator" => "GreaterThan", "value" => "2012-08-10"),
            array("attribute" => "scheduledend", "operator" => "LessThan", "value" => "2012-08-16")
        );
        $obj = new Appointment();
        $arrayOf = $obj->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple objects" );
    }
    
}