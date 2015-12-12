<?php

class EquipmentTest extends \Codeception\TestCase\Test
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
    
    public function testRetrieveSingleEquipment() {
        $obj = new Equipment();
        $equipment = $obj->RetrieveSingle( "ABCF90E9-0512-E111-933B-1CC1DE086845" );
        $this->assertEquals( $equipment->name, "Matteo" );
        $this->assertEquals( $equipment->siteid->name, "Quattro Cantoni" );
        $this->assertEquals( strtoupper( $equipment->siteid->id ), TopBikeConstants::$_SITE_ID["Quattro Cantoni"] );
        
        $equipment = $obj->RetrieveSingle( "B6099712-571D-E311-AF02-3C4A92DBD80A" );
        $this->assertEquals( $equipment->name, "142 (58 ARB) Race Actinum 6000" );
        $this->assertEquals( $equipment->tb_type, TopBikeConstants::$_EQUIPMENT_TYPE[ "Bike" ]);
    }
    
    public function testRetrieveMultipleEquipments() {
        $conditions = array( 
            array("attribute" => "tb_type", "operator" => "Equal", "value" => TopBikeConstants::$_EQUIPMENT_TYPE["Guide"]),
            array("attribute" => "name", "operator" => "Like", "value" => "Ma%")
        );
        $obj = new Equipment();
        $arrayOf = $obj->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple equipments" );
    }
    
    public function testAvaibility() {
        
        $obj = new Equipment();
        $resources = array( "B6099712-571D-E311-AF02-3C4A92DBD80A",
                            "FE089712-571D-E311-AF02-3C4A92DBD80A",
                            "F2089712-571D-E311-AF02-3C4A92DBD80A" );

	$start_date = "2000-02-01T10:00:00";
	$end_date = "2000-02-02T11:00:00";
	$avaibility = $obj->checkAvaibility($resources, $start_date, $end_date, "0 hour");
        // fwrite(STDERR, print_r( $avaibility, TRUE ));
        $this->assertEquals( true, $avaibility["B6099712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );

	$start_date = "2000-02-01T12:00:00";
	$end_date = "2000-02-06T12:00:00";
	$avaibility = $obj->checkAvaibility( $resources, $start_date, $end_date, "0 hour" );
	$this->assertEquals( true, $avaibility["B6099712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
        $this->assertEquals( true, $avaibility["FE089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
        $this->assertEquals( true, $avaibility["F2089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );

	$start_date = "2000-03-01T14:00:00";
	$end_date = "2000-03-01T15:00:00";
	$avaibility = $obj->checkAvaibility( $resources, $start_date, $end_date, "0 hour" );
	$this->assertEquals( true, $avaibility["B6099712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
        $this->assertEquals( true, $avaibility["FE089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
        $this->assertEquals( true, $avaibility["F2089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );

	$start_date = "2000-02-01T09:00:00";
	$end_date = "2000-02-01T09:30:00";
	$avaibility = $obj->checkAvaibility( $resources, $start_date, $end_date, "0 hour" );
	$this->assertEquals( true, $avaibility["B6099712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
        $this->assertEquals( true, $avaibility["FE089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
        $this->assertEquals( true, $avaibility["F2089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );

	$start_date = "2000-02-08T09:00:00";
	$end_date = "2000-02-15T18:00:00";
	$avaibility = $obj->checkAvaibility( $resources, $start_date, $end_date, "0 hour" );
	$this->assertEquals( true, $avaibility["B6099712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
        $this->assertEquals( true, $avaibility["FE089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );
	$this->assertEquals( false, $avaibility["F2089712-571D-E311-AF02-3C4A92DBD80A"]["availability"] );

    }
}