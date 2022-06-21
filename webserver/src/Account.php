<?php

class Account
{
    // Class variables
    protected $account_id;
    protected $first_name;
    protected $last_name;
    protected $email;
    protected $phone;
    protected $address;
    protected $lat;
    protected $long;
    protected $account_data;

    function __construct($username, $password, $user_type)
    {
        /*
         * Account constructor. Initialises an Account object based on
         * the username and password passed in to the function.
         */
        // Set DB location based on whether we are running the website or unit tests
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/users.db" : "webserver/res/users.db";

        // Connect to database
        $pdo = new \PDO("sqlite:" . $db_location);

        // Set table name based on user type
        if ($user_type == "customer")
        {
            $stmt = $pdo->prepare('SELECT * FROM Customer WHERE Email = :username AND Password = :password;');
        }
        else if ($user_type == "professional")
        {
            $stmt = $pdo->prepare('SELECT * FROM Professional WHERE Email = :username AND Password = :password;');
        }
        else
        {
            $this->account_id = -1;
            return;
        }

        // See if username and password exist
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, user does not exist. Set account id to -1 and return.
        if (count($data) < 1)
        {
            $this->account_id = -1;
            return;
        }

        // Set variables based on database result
        $this->account_id = $username;
        $this->first_name = $data[0]['FirstName'];
        $this->last_name = $data[0]['LastName'];
        $this->email = $data[0]['Email'];
        $this->phone = $data[0]['Phone'];
        $this->address = $data[0]['Lat'] . ',' . $data[0]['Lon'];
        $this->lat = $data[0]['Lat'];
        $this->long = $data[0]['Lon'];
        $this->account_data = $data;
    }

    public function register($first_name, $last_name, $email, $phone, $address)
    {
        /*
         * Register an account. Creates an account with the passed-in information
         * by adding it to the customer database. The account ID is the user's email address.
         */
        // Set class variables using parameters
        $this->account_id = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function getLocation()
    {
        /*
         * Return the user's location.
         */
        return $this->address;
    }

    public function getLat()
    {
        /*
         * Return the user's location.
         */
        return $this->lat;
    }

    public function getLong()
    {
        /*
         * Return the user's location.
         */
        return $this->long;
    }

    public function getID()
    {
        /*
         * Returns the account's ID.
         */
        return $this->account_id;
    }

    public function getName()
    {
        /*
         * Returns the customer's name.
         */
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getPhoneNumber()
    {
        /*
         * Returns the customer's phone number.
         */
        return $this->phone;
    }

    public function getEmail()
    {
        /*
         * Return the customer's email.
         */
        return $this->email;
    }
}
