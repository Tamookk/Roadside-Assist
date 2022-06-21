<?php

include 'Account.php';

class Customer extends Account
{
    // Class variables
    private $registration;
    private $subscribed;

    function __construct($username, $password, $user_type)
    {
        /*
         * Customer constructor. Calls the Account constructor with
         * the passed in username, then sets up the rest of the user.
         */
        parent::__construct($username, $password, $user_type);

        // Return if customer does not exist
        if ($this->account_id == -1)
        {
            return;
        }

        // Set the customer's registration and subscription status
        $this->registration = $this->account_data[0]['Registration'];
        $this->subscribed = $this->account_data[0]['Subscribed'];
    }

    public function getAddress()
    {
        /*
         * Return the customer's address.
         */
        return $this->address;
    }

    // Get registration
    public function getRegistration()
    {
        /*
         * Return the customer's registration.
         */
        return $this->registration;
    }

    // Set registration
    public function setRegistration($registration)
    {
        /*
         * Sets the customer's registration.
         */
        $this->registration = $registration;
    }

    public function getSubscriptionStatus()
    {
        /*
         * Returns the customer's subscription status. Returns "checked" if the customer is subscribed,
         * empty string if not.
         */
        if ($this->subscribed == true)
        {
            return "checked";
        }
        else
        {
            return '';
        }
    }

    public function setSubscriptionStatus($subscription_status)
    {
        /*
         * Updates a customer's subscription status.
         */
        $this->subscribed = $subscription_status;
        // TODO: update database
    }
}