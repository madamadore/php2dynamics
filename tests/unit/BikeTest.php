<?php

class BikeTest extends \Codeception\TestCase\Test
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
    
    public function testRetrieveSingleBike() {
        $obj = new Bike();
        $bike = $obj->RetrieveSingle( "CD91053B-9DA8-E411-80D7-FC15B4280CB8" );
        $this->assertEquals( $bike->tb_name, "481 - Trail 2 (47.5)" );
        $this->assertEquals( $bike->tb_framesize, 47 );
    }
    
    public function testRetrieveMultipleBikes() {
        $conditions = array( 
            array("attribute" => "tb_name", "operator" => "Like", "value" => "%Lady%")
        );
        $obj = new Bike();
        $arrayOf = $obj->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple bikes" );
    }

}