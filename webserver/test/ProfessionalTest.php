<?php
use PHPUnit\Framework\TestCase;

include "webserver/src/Professional.php";

class ProfessionalTest extends TestCase
{
    // Professional object variable
    private Professional $professional;

    public function setUp(): void
    {
        /*
         * Set up the test module. Create a Payment object with dummy data.
         */
        // Create Professional object
        $this->professional = new Professional('username', 'password', 'professional');
        $this->professional->register('John', 'Doe', '', '', '123 Fake St');
        $this->professional->setBusinessName('AAA Holdings');
        $this->professional->setRating(4);
    }

    public function testGetBusinessName()
    {
        /*
         * Assert that the Professional's business name is properly set and retrieved.
         */
        $this->assertSame('AAA Holdings', $this->professional->getBusinessName());
    }

    public function testSetBusinessName()
    {
        /*
         * Tests that the Professional's business name can be correctly set.
         */
        // Change Professional's business name and assert the change happened properly
        $this->professional->setBusinessName("AAB Holdings");
        $this->assertSame("AAB Holdings", $this->professional->getBusinessName());

        // Reset Professional's business name
        $this->professional->setBusinessName("AAA Holdings");
    }

    public function testGetRating()
    {
        /*
         * Assert that the Professional's rating is properly set and retrieved.
         */
        $this->assertSame(4, $this->professional->getRating());
    }

    public function testSetRating()
    {
        /*
         * Assert that the Professional's rating can be correctly set.
         */
        // Change Professional's rating and assert the change happened properly
        $this->professional->setRating(4.5);
        $this->assertSame(4.5, $this->professional->getRating());

        // Reset Professional's rating
        $this->professional->setRating(4);
    }

    public function testGetLocation()
    {
        /*
         * Assert that the Professional's location is properly set and retrieved.
         */
        $this->assertSame('123 Fake St', $this->professional->getLocation());
    }
}
