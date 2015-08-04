<?php

Codeception\Specify\Config::setIgnoredProperties(['user']);

class AccountTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $user;

    protected function _before()
    {        
        $user = new Account();
        $user->name = "Test Account";
        $user->description = "This Account is a test one. You can safely delete it if you catch him";
        
        $this->user = $user;
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateAccount()
    {
        $this->specify("Create account", function() {
            $generatedId = $this->user->Create();
            $this->assertNotEmpty( $generatedId );
            $this->user->setGuid( $generatedId );
        });

        $this->specify("Update account", function() {
            $this->user->name = "Matteo Avanzini";
            $update = $this->user->Update();
            $this->assertTrue( $update, "Account has been updated" );
        });
        
        $this->specify("Retrieve account", function() {
            $user = new Account();
            $matteo = $user->RetrieveSingle( $this->user->getGuid() );
            $this->assertTrue( isset( $matteo->name ), "Property name exists" );
            $this->assertEquals( "Matteo Avanzini", $matteo->name, "Name updated" );
        });

        $this->specify("Delete account", function() {
            $delete = $this->user->Delete();
            $this->assertTrue( $delete, "Account deleted");
        });

        $this->specify("Retrieve deleted account", function() {
            $user = new Account();
            $deleted = $user->RetrieveSingle( $this->user->getGuid() );
            $this->assertNull( $deleted, "Deleted object is null" );
        });
    }
    
    public function testRetrieveMultiple() {
        $conditions = array( 
            array("attribute" => "name", "operator" => "Like", "value" => "Camp%")
        );
        $user = new Account();
        $arrayOf = $user->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple objects" );
    }

}