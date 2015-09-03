<?php

class AccountTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $account;

    protected function _before()
    {        
        $account = new Account();
        $account->name = "Test Account";
        $account->description = "This Account is a test one. You can safely delete it if you catch him";
        
        $this->account = $account;
    }

    protected function _after()
    {
    }

    // tests
    public function testCrudAccount()
    {
        $this->specify("Create account", function() {
            $generatedId = $this->account->Create();
            $this->assertTrue( (boolean) preg_match("/[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}/", $generatedId ) );
            $this->account->setGuid( $generatedId );
        });

        $this->specify("Update account", function() {
            $this->account->name = "Matteo Avanzini";
            $update = $this->account->Update();
            $this->assertTrue( $update, "Account has been updated" );
        });
        
        $this->specify("Retrieve account", function() {
            $account = new Account();
            $matteo = $account->RetrieveSingle( $this->account->getGuid() );
            $this->assertTrue( isset( $matteo->name ), "Property name exists" );
            $this->assertEquals( "Matteo Avanzini", $matteo->name, "Name updated" );
        });

        $this->specify("Delete account", function() {
            $delete = $this->account->Delete();
            $this->assertTrue( $delete, "Account deleted");
        });

        $this->specify("Retrieve deleted account", function() {
            $account = new Account();
            $deleted = $account->RetrieveSingle( $this->account->getGuid() );
            $this->assertNull( $deleted, "Deleted object is null" );
        });
    }
    
    public function testRetrieveMultipleAccounts() {
        $conditions = array( 
            array("attribute" => "name", "operator" => "Like", "value" => "%Rome%")
        );
        $user = new Account();
        $arrayOf = $user->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple objects" );
    }

}