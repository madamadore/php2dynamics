<?php

Codeception\Specify\Config::setIgnoredProperties(['user']);

class ProductTest extends \Codeception\TestCase\Test
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
    
    public function testRetrieveSingleProduct() {
        $obj = new Product();
        $product = $obj->RetrieveSingle( "1A0A4660-AD48-E411-A81A-D89D67638EE8" );
        $this->assertEquals( $product->name, "City Center Summer" );
        $this->assertEquals( $product->tb_servicetype, TopBikeConstants::$_SERVICE_TYPE["scheduled_tour"] );
    }
    
    public function testRetrieveMultipleProducts() {
        $conditions = array( 
            array("attribute" => "tb_servicetype", "operator" => "Equal", "value" => TopBikeConstants::$_SERVICE_TYPE["scheduled_tour"]),
            array("attribute" => "price", "operator" => "GreaterThan", "value" => "30"),
            
        );
        $obj = new Product();
        $arrayOf = $obj->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple bikes" );
    }
}