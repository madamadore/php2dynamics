<?php

class BikeRentalTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $bikerent;
    
    protected function _before()
    {   
        $br = new BikeRental();
        $br->description = "This is test a rental booking";
        $br->regardingobjectid = "47f0189b-1bba-e111-b50b-d4856451dc79"; // Rimmer Lankaster
        $br->resources = array ( "B6099712-571D-E311-AF02-3C4A92DBD80A", "F2089712-571D-E311-AF02-3C4A92DBD80A" );
        $br->tb_scheduledbikes = 2;
        $br->scheduledstart = "2000-01-01T10:00:00";
        $br->scheduledend = "2000-01-01T14:00:00";
        $br->siteid = TopBikeConstants::$_SITE_ID[ "Quattro Cantoni" ];
        $br->tb_language = TopBikeConstants::$_LANGUAGE[ "IT" ];
        $br->tb_participants = 1;
        $br->tb_bookingdate = "2010-08-10T19:34:58";
        $br->tb_deposit = 20;
        $br->tb_openamount = 80;
        $br->tb_totalamount = 100;
        $br->subject = "Bike Rental Test";
        $this->bikerent = $br;
    }

    protected function _after()
    {
    }
    
    public function testCrudBooking() {
        
        $this->specify("Create bike rental", function() {
            $generatedId = $this->bikerent->Create();
            $this->assertTrue( (boolean) preg_match("/[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}/", $generatedId ) );
            $this->bikerent->setGuid( $generatedId );
        });

        $this->specify("Retrieve bike rental", function() {
            $obj = new BikeRental();
            $bikerent = $obj->RetrieveSingle( $this->bikerent->getGuid() );
            $this->assertTrue( isset( $bikerent->tb_participants ), "Property participants exists" );
            $this->assertEquals( 1, $bikerent->tb_participants, "Participants is one" );
            $this->assertEquals( 2, $bikerent->tb_scheduledbikes, "Scheduled bikes is two" );
        });

        $this->specify("Delete bike rental", function() {
            $delete = $this->bikerent->Delete();
            $this->assertTrue( $delete, "Contact deleted");
        });

        $this->specify("Retrieve deleted bike rental", function() {
            $bikerent = new BikeRental();
            $deleted = $bikerent->RetrieveSingle( $this->bikerent->getGuid() );
            $this->assertNull( $deleted, "Deleted object is null" );
        });
    }
    
}