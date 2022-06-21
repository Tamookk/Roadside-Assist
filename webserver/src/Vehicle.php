<?php

class Vehicle
{
    // Class variables
    private $make;
    private $model;
    private $registration;
    private $condition;
    private $year;
    private $vehicle_data;

    function __construct($registration, $make = '', $model = '', $condition = '', $year = '')
    {
        /*
         * Constructor. Initialises object. Either provide with a registration only to automatically populate data
         * from database, or provide all parameters to create vehicle manually.
         */
        // Initialise class variables
        $this->registration = $registration;

        // Create vehicle manually
        if ($make != '' && $model != '' && $condition != '' && $year != '')
        {
            $this->make = $make;
            $this->model = $model;
            $this->condition = $condition;
            $this->year = $year;
            return;
        }

        // Attempt to get vehicle data from database.
        // Set DB location based on whether we are running the website or unit tests.
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/vehicle.db" : "res/vehicle.db";

        // Connect to database
        $pdo = new \PDO("sqlite:" . $db_location);

        // See if registration exists in DB (it should!)
        $stmt = $pdo->prepare('SELECT * FROM Vehicle WHERE Registration = :registration;');
        $stmt->bindParam(':registration', $registration);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, registration does not exist. Set registration to -1 and return.
        if (count($data) < 1)
        {
            $this->registration = -1;
            return;
        }

        // Set variables based on database result
        $this->make = $data[0]['Make'];
        $this->model = $data[0]['Model'];
        $this->condition = $data[0]['Condition'];
        $this->year = $data[0]['Year'];
        $this->vehicle_data = $data;
    }

    public function getMake()
    {
        /*
         * Return the make of the vehicle.
         */
        return $this->make;
    }

    public function getModel()
    {
        /*
         * Return the model of the vehicle.
         */
        return $this->model;
    }

    public function getRegistration()
    {
        /*
         * Return the registration of the vehicle.
         */
        return $this->registration;
    }

    public function getCondition()
    {
        /*
         * Return the condition of the vehicle.
         */
        return $this->condition;
    }

    public function getYear()
    {
        /*
         * Return the year of the vehicle.
         */
        return $this->year;
    }
}