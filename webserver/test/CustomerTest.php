<?php
use PHPUnit\Framework\TestCase;

include "webserver/src/Customer.php";

class CustomerTest extends TestCase
{
    // Account object variable
    private Customer $customer;

    protected function setUp(): void
    {
        /*
         * Set up the test module. Create a Customer object and register it.
         */
        // Create and register customer
        $this->customer = new Customer('username', 'password', 'customer');
        $this->customer->register('John', 'Doe', '', '', '123 Fake St');

        // Update customer details
        $this->customer->setRegistration("AAAAAA");
        $this->customer->setSubscriptionStatus(true);
    }

    public function testGetAddress()
    {
        /*
         * Tests that the Customer class has its location properly set and received.
         */
        $this->assertSame('123 Fake St', $this->customer->getAddress());
    }

    public function testGetRegistration()
    {
        /*
         * Tests that the Customer class has its registration properly set and received.
         */
        $this->assertSame('AAAAAA', $this->customer->getRegistration());
    }

    public function testSetRegistration()
    {
        /*
         * Tests that the Customer's registration can be properly updated.
         */
        $this->customer->setRegistration("AAAAAB");
        $this->assertSame("AAAAAB", $this->customer->getRegistration());

        // Reset Customer's registration
        $this->customer->setRegistration("AAAAAA");
    }

    public function testGetSubscriptionStatus()
    {
        /*
         * Tests that the customer's subscription status is correctly set.
         */
        $this->assertSame('checked', $this->customer->getSubscriptionStatus());
    }

    public function testSetSubscriptionStatus()
    {
        /*
         * Tests that the customer's subscription status can be properly updated.
         */
        // Test that the subscription status can be properly updated
        $this->customer->setSubscriptionStatus(false);
        $this->assertSame('', $this->customer->getSubscriptionStatus());

        // Change subscription status back to original value
        $this->customer->setSubscriptionStatus(true);
    }
}
