<?php
use PHPUnit\Framework\TestCase;


class AccountTest extends TestCase
{
    // Account object variable
    private Account $account;

    protected function setUp(): void
    {
        /*
         * Set up the test module. Create an Account object and register it.
         */
        $this->account = new Account('username', 'password', 'customer');
        $this->account->register('John', 'Doe', 'test@test.com', '0000000000', '123 Fake St');
    }

    public function testRegister()
    {
        /*
         * Test the register function of the Account class. Executes the register function with new data then
         * asserts that all account details were properly set. Replaces data when finished.
         */
        $this->account->register('Jane', 'Doe', 'test1@test.com', '0000000001', '124 Fake St');

        // Assert that account details were correctly set
        $this->assertSame('test1@test.com', $this->account->getID());
        $this->assertSame('Jane Doe', $this->account->getName());
        $this->assertSame('test1@test.com', $this->account->getEmail());
        $this->assertSame('0000000001', $this->account->getPhoneNumber());
        $this->assertSame('124 Fake St', $this->account->getLocation());

        // Replace old data
        $this->account->register('John', 'Doe', 'test@test.com', '0000000000', '123 Fake St');
    }

    public function testGetLocation()
    {
        /*
         * Assert that the getLocation function works by asserting that the Account's set location is as expected.
         */
        // Assert location correctly set and retrieved
        $this->assertSame('123 Fake St', $this->account->getLocation());
    }

    public function testGetID()
    {
        /*
         * Assert that the account's username is the same as the email, and is as expected.
         */
        $this->assertSame('test@test.com', $this->account->getID());
        $this->assertSame($this->account->getID(), $this->account->getEmail());
    }

    public function testGetName()
    {
        /*
         * Assert that the account's name is the same as what is expected.
         */
        $this->assertSame('John Doe', $this->account->getName());
    }

    public function testGetPhoneNumber()
    {
        /*
         * Assert that the getLocation function works by asserting that the Account's set location is as expected.
         */
        // Assert location correctly set and retrieved
        $this->assertSame('0000000000', $this->account->getPhoneNumber());
    }

    public function testGetEmail()
    {
        /*
         * Assert that the getLocation function works by asserting that the Account's set location is as expected.
         */
        // Assert location correctly set and retrieved
        $this->assertSame('test@test.com', $this->account->getEmail());
    }
}
