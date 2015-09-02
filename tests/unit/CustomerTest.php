<?php

// Codeception\Specify\Config::setIgnoredProperties(['user']);

class CustomerTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $user;

    protected function _before()
    {        
        $user = new Customer("User Test");
        $user->firstname = "User";
        $user->lastname = "Test";
        $user->emailaddress1 = "lomion@tiscali.it";
        $user->mobilephone = "0123456789";
        $user->description = "This user is a test one. You can safely delete it if you catch him";
        
        $this->user = $user;
    }

    protected function _after()
    {
    }

    // tests
    public function testCrudCustomer()
    {
        $this->specify("Create customer", function() {
            $generatedId = $this->user->Create();
            $this->assertTrue( (boolean) preg_match("/[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}/", $generatedId ) );
            $this->user->setGuid( $generatedId );
        });

        $this->specify("Update customer", function() {
            $this->user->emailaddress1 = "matteo.avanzini@gmail.com";
            $update = $this->user->Update();
            $this->assertTrue( $update, "User has been updated" );
        });
        
        $this->specify("Retrieve customer", function() {
            $user = new Customer();
            $matteo = $user->RetrieveSingle( $this->user->getGuid() );
            $this->assertTrue( isset( $matteo->emailaddress1 ), "Property emailaddress1 exists" );
            $this->assertEquals( "matteo.avanzini@gmail.com", $matteo->emailaddress1, "Email updated" );
        });

        $this->specify("Delete customer", function() {
            $delete = $this->user->Delete();
            $this->assertTrue( $delete, "Contact deleted");
        });

        $this->specify("Retrieve deleted customer", function() {
            $user = new Customer();
            $deleted = $user->RetrieveSingle( $this->user->getGuid() );
            $this->assertNull( $deleted, "Deleted object is null" );
        });
    }
    
    public function testRetrieveMultipleContacts() {
        $conditions = array( 
            array("attribute" => "firstname", "operator" => "Like", "value" => "Matteo%")
        );
        $user = new Customer();
        $arrayOf = $user->RetrieveMultiple( $conditions );
        $this->assertTrue( is_array($arrayOf) && count($arrayOf) > 0, "Multiple retrieve returns multiple objects" );
    }
    
    public function testFails() {
        $user = new Customer("User Error");
        $user->firstname = "User";
        $user->lastname = "Test";

        $update = $user->Update();

        $this->assertTrue( is_string( $update ), "Error returns string messages" );
        $this->assertEquals( "Entity Id must be specified for Update", $update );
    }

}