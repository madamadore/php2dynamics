<?php

class BikeModelTest extends \Codeception\TestCase\Test
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
    
    public function testRetrieveSingleBikeModel() {
        $obj = new BikeModel();
        $bikeModel = $obj->RetrieveSingle( "DDF49BE8-9CA8-E411-80D7-FC15B4280CB8" );
        $this->assertTrue( is_object( $bikeModel ), "Single object bike model retrived" );
        $this->assertEquals( $bikeModel->tb_name, "Trail 2" );
    }
    
    public function testRetrieveMultipleBikeModels() {
        $conditions = array( 
            array("attribute" => "tb_brand", "operator" => "Equal", "value" => TopBikeConstants::$_BIKE_BRAND["Cannondale"])
        );
        $obj = new BikeModel();
        $arrayOf = $obj->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple Cannondale Bike models retrieved" );
    }
    
}