<?php

class Waitlist
{
    protected $caseID;
    protected $username;
    protected $timestamp;
    protected $registration;
    protected $lat;
    protected $lon;
    protected $phone;
    protected $subscribed;
    protected $clientName;
    protected $vehicleDetails;
    protected $description;
    protected $distance;
    protected $professional;
    protected $company;
    protected $userRating;
    protected $driverRating;
    protected $cost;

    function __construct()
    {
        // Initialise class variables
        $caseID = 0;
        $username = null;
        $timestamp = null;
        $registration = null;
        $lat = null;
        $lon = null;
        $phone = null;
        $subscribed = null;
        $clientName = null;
        $vehicleDetails = null;
        $description = null;
        $distance = null;
        $professional = null;
        $company = null;
        $userRating = null;
        $driverRating = null;
        $cost = null;
    }

    public function populate($username, $registration, $lat, $lon, $phone, $subscribed, $clientName, $vehicleDetails, $description)
    {
        /*
         * Populate the waitlist with passed-in data and set the case ID.
         */
        $this->username = $username;
        $this->timestamp = time();
        $this->registration = $registration;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->phone = $phone;
        $this->subscribed = $subscribed;
        $this->clientName = $clientName;
        $this->vehicleDetails = $vehicleDetails;
        $this->description = $description;

        // Get latest case ID
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdoc = new \PDO("sqlite:" . $db_location);
        $stmt = $pdoc->prepare('select max(v) from (SELECT CaseID as v from waiting union SELECT CaseID from closed union SELECT CaseID from active);');
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // Set current case ID to most recent one + 1
        $this->caseID = intval($data[0]['max(v)'])+1;
    }

    public function pull($caseID)
    {
        /*
         * Pull info on the case with the passed-in case ID. Returns -1 if case does not exist.
         */
        $this->caseID = intval($caseID);

        // Connect to database and retrieve case information
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Retrieve case from waiting table
        $stmt = $pdo->prepare('SELECT * FROM waiting WHERE CaseID = :case_id;');
        $stmt->bindParam(':case_id', $this->caseID);
        $stmt->execute();
        $wdata = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $wdata = mb_convert_encoding($wdata, 'UTF-8', 'UTF-8');

        // Retrieve case from active table
        $stmt = $pdo->prepare('SELECT * FROM active WHERE CaseID = ' . $caseID);
        $stmt->execute();
        $adata = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $adata = mb_convert_encoding($adata, 'UTF-8', 'UTF-8');

        // Retrieve case from closed table
        $stmt = $pdo->prepare('SELECT * FROM closed WHERE CaseID = ' . $caseID);
        $stmt->execute();
        $cdata = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $cdata = mb_convert_encoding($cdata, 'UTF-8', 'UTF-8');

        // If case is in waiting table
        if (count($wdata) == 1)
        {
            $this->registration = $wdata[0]['Registration'];
            $this->clientName = $wdata[0]['ClientName'];
            $this->subscribed = $wdata[0]['Subscribed'];
            $this->phone = $wdata[0]['Phone'];
            $this->lat = $wdata[0]['Lat'];
            $this->lon = $wdata[0]['Lon'];
            $this->timestamp = $wdata[0]['timestamp'];
            $this->vehicleDetails = $wdata[0]['vehicleDetails'];
            $this->username = $wdata[0]['email'];
            $this->description = $wdata[0]['description'];
        }
        // If case in in active table
        else if (count($adata) == 1)
        {
            $this->registration = $adata[0]['Registration'];
            $this->clientName = $adata[0]['ClientName'];
            $this->subscribed = $adata[0]['Subscribed'];
            $this->phone = $adata[0]['Phone'];
            $this->lat = $adata[0]['Lat'];
            $this->lon = $adata[0]['Lon'];
            $this->timestamp = $adata[0]['timestamp'];
            $this->vehicleDetails = $adata[0]['vehicleDetails'];
            $this->username = $adata[0]['email'];
            $this->description = $adata[0]['description'];
            $this->distance = $adata[0]['distance'];
            $this->professional = $adata[0]['professional'];
            $this->company = $adata[0]['company'];
        }
        // If case is in closed table
        else if (count($cdata) == 1)
        {
            $this->registration = $cdata[0]['Registration'];
            $this->clientName = $cdata[0]['ClientName'];
            $this->subscribed = $cdata[0]['Subscribed'];
            $this->phone = $cdata[0]['Phone'];
            $this->lat = $cdata[0]['Lat'];
            $this->lon = $cdata[0]['Lon'];
            $this->timestamp = $cdata[0]['timestamp'];
            $this->vehicleDetails = $cdata[0]['vehicleDetails'];
            $this->username = $cdata[0]['email'];
            $this->description = $cdata[0]['description'];
            $this->distance = $cdata[0]['distance'];
            $this->professional = $cdata[0]['professional'];
            $this->company = $cdata[0]['company'];
            $this->cost = $cdata[0]['cost'];
        }
        // Otherwise, case does not exist
        else
        {
            $this->caseID = -1;
        }
    }

    public function setUsername($username)
    {
        /*
         * Set username of the case.
         */
        $this->username = $username;
    }

    public function getUsername()
    {
        /*
         * Get username of the case.
         */
        return $this->username;
    }

    public function setTimeStamp()
    {
        /*
         * Set timestamp of the case.
         */
        $this->timestamp = time();
    }

    public function getTimeStamp()
    {
        /*
         * Get timestamp of the case.
         */
        return $this->timestamp;
    }

    public function setRegistration($registration)
    {
        /*
         * Set registration of the case.
         */
        $this->registration = $registration;
    }

    public function getRegistration()
    {
        /*
         * Get registration.
         */
        return $this->registration;
    }

    public function setLat($lat)
    {
        /*
         * Set latitude of the case.
         */
        $this->lat = $lat;
    }

    public function getLat()
    {
        /*
         * Get latitude.
         */
        return $this->lat;
    }

    public function setlon($lon)
    {
        /*
         * Set longitude of the case.
         */
        $this->lon = $lon;
    }

    public function getlon()
    {
        /*
         * Get longitude.
         */
        return $this->lon;
    }

    public function setPhone($phone)
    {
        /*
         * Set phone of the case.
         */
        $this->phone = $phone;
    }

    public function getPhone()
    {
        /*
         * Get phone number.
         */
        return $this->phone;
    }

    public function setSubscribed($subscribed)
    {
        /*
         * Set subscription status of the case.
         */
        $this->subscribed = $subscribed;
    }

    public function getSubscribed()
    {
        /*
         * Get subscription status.
         */
        return $this->subscribed;
    }

    public function setName($clientName)
    {
        /*
         * Set name of the case.
         */
        $this->clientName = $clientName;
    }

    public function getName()
    {
        /*
         * Get customer name.
         */
        return $this->clientName;
    }

    public function setVehicle($vehicleDetails)
    {
        /*
         * Set vehicle details of the case.
         */
        $this->vehicleDetails = $vehicleDetails;
    }

    public function getVehicle()
    {
        /*
         * Get vehicle details.
         */
        return $this->vehicleDetails;
    }

    public function setCaseID($case_id)
    {
        /*
         * Set the case ID.
         */
        $this->caseID = $case_id;
    }

    public function getCaseID()
    {
        /*
         * Get case ID.
         */
        return $this->caseID;
    }

    public function setDistance($proLat, $prolon)
    {
        /*
         * Set distance between professional and user.
         */
        $clat = deg2rad(floatval($this->lat));
        $plat = deg2rad(floatval($proLat));
        $clon = deg2rad(floatval($this->lon));
        $plon = deg2rad(floatval($prolon));

        //Haversine Formula
        $dlon = $plon - $clon;
        $dlat = $plat - $clat;
        $val = pow(sin($dlat/2),2)+cos($clat)*cos($plat)*pow(sin($dlon/2),2);
        $res = 2 * asin(sqrt($val));
        $radius = 3958.756;
        $distance = ($res*$radius)*1.609344;
        return round($distance);
    }

    public function getDistance()
    {
        /*
         * Get saved distance
         */
        return $this->distance;
    }

    public function getCompany()
    {
        /*
         * Get business name of professional.
         */
        return $this->company;
    }

    public function getDescription()
    {
        /*
         * Get description of case.
         */
        return $this->description;
    }

    public function writeToDB()
    {
        /*
         * Write case data to waiting table in the database.
         */
        // Connect to database
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Try insert into waiting database
        try
        {
            $stmt = $pdo->prepare("INSERT INTO waiting ('Registration', 'ClientName', 'Subscribed', 'Phone', 'Lat', 'lon', 'timestamp', 'CaseID', 'vehicleDetails', 'email', 'description') VALUES (:registration, :clientName, :subscribed, :phone, :lat, :lon, :timestamp, :caseID, :vehicleDetails, :username, :description);");
            $stmt->bindParam(':registration', $this->registration);
            $stmt->bindParam(':clientName', $this->clientName);
            $stmt->bindParam(':subscribed', $this->subscribed);
            $stmt->bindParam(':phone', $this->phone);
            $stmt->bindParam(':lat', $this->lat);
            $stmt->bindParam(':lon', $this->lon);
            $stmt->bindParam(':timestamp', $this->timestamp);
            $stmt->bindParam(':caseID', $this->caseID);
            $stmt->bindParam(':vehicleDetails', $this->vehicleDetails);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':description', $this->description);
            $stmt->execute();
        }
        // Catch any errors
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function setToActive($distance, $professional, $company)
    {
        /*
         * Set case to active - remove from waiting table and add to active table.
         */
        // Set variables
        $this->distance = $distance;
        $this->professional = $professional;
        $this->company = $company;

        // Conntect to database
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Try insert into active table
        try
        {
            $stmt = $pdo->prepare("INSERT INTO active ('Registration', 'ClientName', 'Subscribed', 'Phone', 'Lat', 'lon', 'timestamp', 'CaseID', 'vehicleDetails', 'email', 'description', 'distance', 'professional', 'company') VALUES (:registration, :clientName, :subscribed, :phone, :lat, :lon, :timestamp, :caseID, :vehicleDetails, :username, :description, :distance, :professional, :company);");
            $stmt->bindParam(':registration', $this->registration);
            $stmt->bindParam(':clientName', $this->clientName);
            $stmt->bindParam(':subscribed', $this->subscribed);
            $stmt->bindParam(':phone', $this->phone);
            $stmt->bindParam(':lat', $this->lat);
            $stmt->bindParam(':lon', $this->lon);
            $stmt->bindParam(':timestamp', $this->timestamp);
            $stmt->bindParam(':caseID', $this->caseID);
            $stmt->bindParam(':vehicleDetails', $this->vehicleDetails);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':distance', $this->distance);
            $stmt->bindParam(':professional', $this->professional);
            $stmt->bindParam(':company', $this->company);
            $stmt->execute();

            // Delete case from waiting table
            $stmt = $pdo->prepare('DELETE FROM waiting WHERE CaseID = :case_id;');
            $stmt->bindParam(':case_id',  $this->caseID);
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function setToClosed($cost)
    {
        /*
         * Set case to closed - remove from active table and add to closed table.
         */
        // Set cost
        $this->cost = $cost;

        // Connect to database
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Try insert into closed table
        try
        {
            $stmt = $pdo->prepare("INSERT INTO closed ('Registration', 'ClientName', 'Subscribed', 'Phone', 'Lat', 'lon', 'timestamp', 'CaseID', 'vehicleDetails', 'email', 'description', 'distance', 'professional', 'company', 'cost') VALUES (:registration, :clientName, :subscribed, :phone, :lat, :lon, :timestamp, :caseID, :vehicleDetails, :username, :description, :distance, :professional, :company, :cost);");
            $stmt->bindParam(':registration', $this->registration);
            $stmt->bindParam(':clientName', $this->clientName);
            $stmt->bindParam(':subscribed', $this->subscribed);
            $stmt->bindParam(':phone', $this->phone);
            $stmt->bindParam(':lat', $this->lat);
            $stmt->bindParam(':lon', $this->lon);
            $stmt->bindParam(':timestamp', $this->timestamp);
            $stmt->bindParam(':caseID', $this->caseID);
            $stmt->bindParam(':vehicleDetails', $this->vehicleDetails);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':distance', $this->distance);
            $stmt->bindParam(':professional', $this->professional);
            $stmt->bindParam(':company', $this->company);
            $stmt->bindParam(':cost', $this->cost);
            $stmt->execute();

            // Delete case from active and/or waiting tables
            $stmt = $pdo->prepare('DELETE FROM active WHERE CaseID = :case_id;');
            $stmt->bindParam(':case_id',  $this->caseID);
            $stmt->execute();

            $stmt = $pdo->prepare('DELETE FROM waiting WHERE CaseID = :case_id;');
            $stmt->bindParam(':case_id',  $this->caseID);
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function setUserRating($userRating)
    {
        /*
         * Set customer rating in closed table.
         */
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);
        $stmt = $pdo->prepare('UPDATE closed SET userRating = :user_rating WHERE CaseID = :case_id;');
        $stmt->bindParam(':user_rating', $userRating);
        $stmt->bindParam(':case_id', $this->caseID);
        $stmt->execute();
    }

    public function setProRating($driverRating)
    {
        /*
         * Set professional rating in closed table.
         */
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);
        $stmt = $pdo->prepare('UPDATE closed SET driverRating = :driver_rating WHERE CaseID = :case_id;');
        $stmt->bindParam(':driver_rating', $driverRating);
        $stmt->bindParam(':case_id', $this->caseID);
        $stmt->execute();
    }
}
