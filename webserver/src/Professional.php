<?php

include_once "Report.php";

class Professional extends Account
{
    // Class variables
    private $business_name;
    private $rating;

    function __construct($username, $password, $user_type)
    {
        /*
         * Constructor. Calls the parent constructor then initialises the rest
         * of the object.
         */
        parent::__construct($username, $password, $user_type);

        // Return if professional does not exist
        if ($this->account_id == -1)
        {
            return;
        }

        $this->rating = Report::getUserRating($username);

        // Set business name
        $this->business_name = $this->account_data[0]['BusinessName'];
    }

    public function getBusinessName()
    {
        /*
         * Return the professional's business name.
         */
        return $this->business_name;
    }

    public function setBusinessName($business_name)
    {
        /*
         * Sets the professional's business name.
         */
        $this->business_name = $business_name;
    }

    public function getRating()
    {
        /*
         * Return the professional's rating.
         */
        return $this->rating;
    }

    public function setRating($rating)
    {
        /*
         * Sets the professional's rating.
         */
        $this->rating = $rating;
    }

    public function getLocation()
    {
        /*
         * Return the professional's location.
         */
        return $this->address;
    }
}