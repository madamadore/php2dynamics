<?php

Codeception\Specify\Config::setIgnoredProperties(['tour', 'bikerent']);

class BookingTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $tour;
    protected $bikerent;
    
    protected function _before()
    {   
        $booking = new Booking();

        $booking->description = "This is a test booking";
        $booking->regardingobjectid = "47f0189b-1bba-e111-b50b-d4856451dc79"; // Rimmer Lankaster
        $booking->tb_productid = "1A0A4660-AD48-E411-A81A-D89D67638EE8";   // City Center Summer
        $booking->tb_tourid = "33600DC8-6221-E311-A9F0-D48564531939"; // City Center
        $booking->serviceid = TopBikeConstants::$_SERVICE_ID[ "Tour" ];
        $booking->resources = array ( 
                    array( "guid" => "CD91053B-9DA8-E411-80D7-FC15B4280CB8", 
                           "logicalName"=>"tb_bike" ) 
        );
        $booking->scheduledstart = "2000-01-01T10:00:00";
        $booking->scheduledend = "2000-01-01T14:00:00";
        $booking->servicetype = TopBikeConstants::$_SERVICE_TYPE[ "scheduled_tour" ];
        $booking->siteid = TopBikeConstants::$_SITE_ID[ "Carlo Botta" ];
        $booking->tb_bookingtype = TopBikeConstants::$_BOOKING_TYPE[ "Web" ];
        $booking->tb_language = TopBikeConstants::$_LANGUAGE[ "EN" ];
        $booking->tb_participants = 3;
        $booking->tb_bookingdate = "2010-08-10T19:34:58";
        $booking->tb_scheduledbikes = 1;
        $booking->tb_deposit = 20;
        $booking->tb_openamount = 80;
        $booking->tb_totalamount = 100;
        $booking->tb_materialdetails = "test notes";
        $booking->subject = "Test Summer City Booking";
        // $booking->tb_topbikerevenue => 100;
        // $booking->tb_tourprice = 100;
        // $booking->tb_productid = "1A0A4660-AD48-E411-A81A-D89D67638EE8";   // City Center Summer
        // $booking->tb_bookingcode = "string",
        $this->tour = $booking;
    }

    protected function _after()
    {
    }
    
    public function testCrudBooking() {
        fwrite(STDERR, print_r( $this->tour, TRUE ));
        $bookingId = $this->tour->Create();
        $this->assertEquals( $bookingId, "This is it!" );
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