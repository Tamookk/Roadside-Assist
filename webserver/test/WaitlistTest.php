<?php
use PHPUnit\Framework\TestCase;

include "webserver/src/Waitlist.php";

class WaitlistTest extends TestCase
{
    private Waitlist $waitlist;

    public function setUp(): void
    {
        /*
         * Initialise Waitlist object.
         */
        // Initialise class
        $this->waitlist = new Waitlist();
    }

    public function testPopulate(): void
    {
        /*
         * Test populating the waitlist with a new case.
         */

        // Populate the database with a new case
        $this->waitlist->populate('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a');

        // If case ID is not 0, the database was populated correctly
        $this->assertNotEquals($this->waitlist->getCaseID(), 0);
    }

    public function testPull(): void
    {
        /*
         * Test pulling a case from the database.
         * Should return -1, as case ID -1 should not exist!
         */
        // Get current case ID
        $current_case_id = $this->waitlist->getCaseID();

        // Pull missing case and assert new case ID is -1
        $this->waitlist->pull(-1);
        $this->assertSame($this->waitlist->getCaseID(), -1);

        // Restore old case ID
        $this->waitlist->setCaseID($current_case_id);
    }

    public function testSetUsername(): void
    {
        /*
         * Test updating the username.
         */
        $this->waitlist->setUsername('b');
        $this->assertSame($this->waitlist->getUsername(), 'b');
    }

    public function testGetUsername(): void
    {
        /*
         * Test getting the username.
         */
        $this->waitlist->setUsername('a');
        $this->assertSame($this->waitlist->getUsername(), 'a');
    }

    public function testSetRegistration(): void
    {
        /*
         * Test setting case registration.
         */
        $this->waitlist->setRegistration('b');
        $this->assertSame($this->waitlist->getRegistration(), 'b');
    }

    public function testGetRegistration(): void
    {
        /*
         * Test getting case registration.
         */
        $this->waitlist->setRegistration('a');
        $this->assertSame($this->waitlist->getRegistration(), 'a');
    }

    public function testSetLat(): void
    {
        /*
         * Test setting latitude.
         */
        $this->waitlist->setLat('b');
        $this->assertSame($this->waitlist->getLat(), 'b');
    }

    public function testGetLat(): void
    {
        /*
         * Test getting latitude.
         */
        $this->waitlist->setLat('a');
        $this->assertSame($this->waitlist->getLat(), 'a');
    }

    public function testSetLon(): void
    {
        /*
         * Test setting longitude
         */
        $this->waitlist->setlon('b');
        $this->assertSame($this->waitlist->getlon(), 'b');
    }

    public function testGetLon(): void
    {
        /*
         * Test getting longitude
         */
        $this->waitlist->setlon('a');
        $this->assertSame($this->waitlist->getlon(), 'a');
    }

    public function testSetPhone(): void
    {
        /*
         * Test setting phone
         */
        $this->waitlist->setPhone('b');
        $this->assertSame($this->waitlist->getPhone(), 'b');
    }

    public function testGetPhone(): void
    {
        /*
         * Test getting phone
         */
        $this->waitlist->setPhone('a');
        $this->assertSame($this->waitlist->getPhone(), 'a');
    }

    public function testSetSubscribed(): void
    {
        /*
         * Test setting subscription status
         */
        $this->waitlist->setSubscribed('b');
        $this->assertSame($this->waitlist->getSubscribed(), 'b');
    }

    public function testGetSubscribed(): void
    {
        /*
         * Test getting subscription status
         */
        $this->waitlist->setSubscribed('a');
        $this->assertSame($this->waitlist->getSubscribed(), 'a');
    }

    public function testSetName(): void
    {
        /*
         * Test setting name
         */
        $this->waitlist->setName('b');
        $this->assertSame($this->waitlist->getName(), 'b');
    }

    public function testGetName(): void
    {
        /*
         * Test getting name
         */
        $this->waitlist->setName('a');
        $this->assertSame($this->waitlist->getName(), 'a');
    }

    public function testSetVehicle(): void
    {
        /*
         * Test setting vehicle details
         */
        $this->waitlist->setVehicle('b');
        $this->assertSame($this->waitlist->getVehicle(), 'b');
    }

    public function testGetVehicle(): void
    {
        /*
         * Test getting vehicle details
         */
        $this->waitlist->setVehicle('a');
        $this->assertSame($this->waitlist->getVehicle(), 'a');
    }

    public function testGetCaseID(): void
    {
        /*
         * Test getting case ID.
         */
        // Assert case ID not -1 (which indicates case does not exist)
        $this->assertNotEquals($this->waitlist->getCaseID(), -1);
    }

    public function testWriteToDB(): void
    {
        /*
         * Test writing case to waiting database.
         */
        // If function does not except, then it works
        try
        {
            $this->waitlist->writeToDB();
            $this->assertSame(1, 1);
        }
        catch (PDOException $e)
        {
            $this->assertSame(0, 1);
        }
    }

    public function testSetToActive(): void
    {
        /*
         * Test that the case can be set to active.
         */
        // If function does not except, then it works
        try
        {
            $this->waitlist->setToActive('a', 'a', 'a');
            $this->assertSame(1, 1);
        }
        catch (PDOException $e)
        {
            $this->assertSame(0, 1);
        }
    }

    public function testGetCompany(): void
    {
        /*
         * Test that the company name updated correctly when setting case to active.
         */
        $this->assertSame($this->waitlist->getCompany(), null);
    }

    public function testSetToClosed(): void
    {
        /*
         * Test that the case successfully was closed.
         */
        // If function does not except, then it works
        try
        {
            $this->waitlist->setToClosed('100');
            $this->assertSame(1, 1);
        }
        catch (PDOException $e)
        {
            $this->assertSame(0, 1);
        }
    }

    public function testSetUserRating(): void
    {
        /*
         * Test that the user rating can be updated properly.
         */
        // If function does not except, then it works
        try
        {
            $this->waitlist->setUserRating(100);
            $this->assertSame(1, 1);
        }
        catch (PDOException $e)
        {
            $this->assertSame(0, 1);
        }
    }

    public function testSetProRating(): void
    {
        /*
         * Test that the pro rating can be updated properly.
         */
        // If function does not except, then it works
        try
        {
            $this->waitlist->setProRating(100);
            $this->assertSame(1, 1);
        }
        catch (PDOException $e)
        {
            $this->assertSame(0, 1);
        }
    }
}
