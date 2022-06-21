<?php
use PHPUnit\Framework\TestCase;

include "webserver/src/Vehicle.php";

class VehicleTest extends TestCase
{
    // Vehicle object variable
    private Vehicle $vehicle;

    public function setUp(): void
    {
        /*
         * Initialise Vehicle object.
         */
        // Initialise class
        $this->vehicle = new Vehicle('AAA111', 'Test', 'Test1', 'New', '2021');
    }

    public function testGetMake()
    {
        /*
         * Test that the vehicle's make is properly set and retrieved.
         */
        $this->assertSame('Test', $this->vehicle->getMake());
    }

    public function testGetModel()
    {
        /*
         * Test that the vehicle's model is properly set and retrieved.
         */
        $this->assertSame('Test1', $this->vehicle->getModel());
    }

    public function testGetRegistration()
    {
        /*
         * Test that the vehicle's registration is properly set and retrieved.
         */
        $this->assertSame('AAA111', $this->vehicle->getRegistration());
    }

    public function testGetCondition()
    {
        /*
         * Test that the vehicle's condition is properly set and retrieved.
         */
        $this->assertSame('New', $this->vehicle->getCondition());
    }

    public function testGetYear()
    {
        /*
         * Test that the vehicle's year is properly set and retrieved.
         */
        $this->assertSame('2021', $this->vehicle->getYear());
    }
}
