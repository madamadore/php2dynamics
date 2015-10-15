<?php

class TourBookingTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $tour;
    
    protected function _before()
    {   
        $booking = new TourBooking();
        $booking->description = "This is a test booking";
        $booking->regardingobjectid = "AD4C86FD-F5B8-E411-80D8-C4346BACEF70"; // Matteo Avanzini
        $booking->tb_tourid = "33600DC8-6221-E311-A9F0-D48564531939"; // Appointement 
        $booking->resources = array ( "B6099712-571D-E311-AF02-3C4A92DBD80A", "F2089712-571D-E311-AF02-3C4A92DBD80A" );
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
        $booking->tb_commission = 0;
        $booking->tb_topbikerevenue = $booking->tb_totalamount - $booking->tb_commission;
        $booking->tb_balance = $booking->tb_topbikerevenue;
        $booking->tb_materialdetails = "test notes";
        $booking->subject = "Test Summer City Booking";
        $this->tour = $booking;
    }

    protected function _after()
    {
    }
    
    public function testCrudBooking() {
        $this->specify("Create booking", function() {
            $generatedId = $this->tour->Create();
            $this->assertTrue( (boolean) preg_match("/[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}/", $generatedId ) );
            $this->tour->setGuid( $generatedId );
        });

        $this->specify("Retrieve booking", function() {
            $tour = new TourBooking();
            $booking = $tour->RetrieveSingle( $this->tour->getGuid() );
            $this->assertTrue( isset( $booking->tb_participants ), "Property participants exists" );
            $this->assertEquals( 3, $booking->tb_participants, "Participants is three" );
            $this->assertEquals( 1, $booking->tb_scheduledbikes, "Scheduled bikes is one" );
        });

        $this->specify("Delete booking", function() {
            $delete = $this->tour->Delete();
            $this->assertTrue( $delete, "Contact deleted");
        });

        $this->specify("Retrieve deleted booking", function() {
            $obj = new TourBooking();
            $deleted = $obj->RetrieveSingle( $this->tour->getGuid() );
            $this->assertNull( $deleted, "Deleted object is null" );
        });
    }
    
    public function testRetrieveSingleBooking() {
        $obj = new Booking();
        $booking = $obj->RetrieveSingle( "E2C37369-CB4C-E511-8123-C4346BACEF70" );
        $this->assertTrue( is_object( $booking ), "Single booking retrived" );
        $this->assertTrue( is_array( $booking->resources ), "Resources is an array" );
        $this->assertEquals( $booking->resources[0]->name, "001 (54 ARB) Dolce 6.9" );
        $this->assertEquals( "Bike Rental Test", $booking->subject );
        $this->assertEquals( "2010-08-10T19:34:58Z", $booking->tb_bookingdate );
    }
    
    public function testRetrieveMultipleBookings() {
        $conditions = array( 
            array("attribute" => "scheduledstart", "operator" => "GreaterThan", "value" => "2012-08-10"),
            array("attribute" => "scheduledend", "operator" => "LessThan", "value" => "2012-08-16")
        );
        $obj = new Booking();
        $arrayOf = $obj->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple objects" );
    }
    
}