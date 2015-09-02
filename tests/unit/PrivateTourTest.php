<?php

class PrivateTourTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $privatetour;
    
    protected function _before()
    {   
        $pt = new PrivateTour();
        $pt->description = "This is a test booking";
        $pt->regardingobjectid = "AD4C86FD-F5B8-E411-80D8-C4346BACEF70"; // Matteo Avanzini
        $pt->tb_productid = "1A0A4660-AD48-E411-A81A-D89D67638EE8";      // City Center Summer
        $pt->resources = array ( array( "guid"=>"FE089712-571D-E311-AF02-3C4A92DBD80A", "logicalName"=>"equipment" ),
                                 array( "guid"=>"F2089712-571D-E311-AF02-3C4A92DBD80A", "logicalName"=>"equipment" ) );
        $pt->scheduledstart = "2000-04-01T09:00:00";
        $pt->scheduledend = "2000-04-01T10:00:00";
        $pt->tb_language = TopBikeConstants::$_LANGUAGE[ "DE" ];
        $pt->tb_bookingcode = "ABCD1";
        $pt->tb_participants = 1;
        $pt->tb_bookingdate = "2010-08-10T19:34:58";
        $pt->tb_scheduledbikes = 0;
        $pt->tb_deposit = 20;
        $pt->tb_openamount = 80;
        $pt->tb_totalamount = 100;
        $pt->tb_commission = 0;
        $pt->tb_topbikerevenue = $pt->tb_totalamount - $pt->tb_commission;
        $pt->tb_balance = $pt->tb_topbikerevenue;
        $pt->tb_materialdetails = "test notes";
        $pt->subject = "Private tour test";
        
        $this->privatetour = $pt;
    }

    protected function _after()
    {
    }
    
    public function testCrudBooking() {
        
        $this->specify("Create private tour", function() {
            $generatedId = $this->privatetour->Create();
            $this->assertTrue( (boolean) preg_match("/[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}/", $generatedId ) );
            $this->privatetour->setGuid( $generatedId );
        });

        $this->specify("Retrieve private tour", function() {
            $tour = new PrivateTour();
            $booking = $tour->RetrieveSingle( $this->privatetour->getGuid() );
            $this->assertTrue( isset( $booking->tb_participants ), "Property participants exists" );
            $this->assertEquals( 1, $booking->tb_participants, "Participants is one" );
            $this->assertEquals( 0, $booking->tb_scheduledbikes, "Scheduled bikes is two" );
        });

        $this->specify("Delete private tour", function() {
            $delete = $this->privatetour->Delete();
            $this->assertTrue( $delete, "Contact deleted");
        });

        $this->specify("Retrieve deleted private tour", function() {
            $obj = new PrivateTour();
            $deleted = $obj->RetrieveSingle( $this->privatetour->getGuid() );
            $this->assertNull( $deleted, "Deleted object is null" );
        });
    }
    
    public function testRetrieveSinglePrivateTour() {
        $obj = new PrivateTour();
        $booking = $obj->RetrieveSingle( "98CB9C02-D83D-E511-8118-C4346BAD5034" );
        $this->assertTrue( is_object( $booking ), "Single booking retrived" );
        $this->assertEquals( $booking->subject, "EN Panoramic Tour" );
        $this->assertEquals( $booking->tb_participants, 5 );
    }
    
}