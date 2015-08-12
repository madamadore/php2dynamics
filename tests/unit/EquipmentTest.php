<?php

Codeception\Specify\Config::setIgnoredProperties(['user']);

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
}