<?php

class ConfigTest extends \Codeception\TestCase\Test
{
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

    // simple stupid test, to ensure that config file has been modified
    public function testConfigFile()
    {
        $config_file = dirname(__FILE__) . '/../../php2dynamics/config.json';
        
        $this->assertTrue( file_exists($config_file), "Config file exists");
        
        $json_file = file_get_contents( $config_file );
        $jfo = json_decode($json_file);

        $this->assertTrue( isset( $jfo->username ), "Username exists" );
        $this->assertTrue( isset( $jfo->password ), "Password exists" );
        $this->assertTrue( isset( $jfo->url ), "Url exists" );
        
        $this->assertNotEquals( "<your username>", $jfo->username );
        $this->assertNotEquals( "<your password>", $jfo->password );
        $this->assertNotEquals( "https://<your url>/XRMServices/2011/Organization.svc", $jfo->url );
    }

}