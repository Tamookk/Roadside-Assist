<?php
    include "../src/Customer.php";
    include "../src/Vehicle.php";
    include "../src/Waitlist.php";

    // Flag for if customer logged in
    $flag = 0;
    $customer = null;
    $vehicle = null;
    $waitlist = null;

    // Check for login cookie
    if (isset($_COOKIE['customer']))
        $flag = 1;

    if ($flag == 1)
    {
      // Get customer
      $cookie_data = $_COOKIE['customer'];
      $cookie_data = explode('|', $cookie_data);
      $customer = new Customer($cookie_data[0], $cookie_data[1], 'customer');

      // If customer does not exist, return
      if ($customer->getID() == -1)
          $flag = 0;
    }

    // Get vehicle data
    if ($flag == 1)
    {
        $vehicle = new Vehicle($customer->getRegistration());
        if ($vehicle->getRegistration() == -1)
            $flag = 0;
    }

    if ($flag == 1)
    {
      // Get vehicle details
      $vehicleDetails = $vehicle->getYear() . ' ' . $vehicle->getMake() . ' ' . $vehicle->getModel();

      // Create and populate waitlist
      $waitlist = new Waitlist();
      $waitlist->populate($customer->getID(), $customer->getRegistration(), $customer->getLat(), $customer->getLong(), $customer->getPhoneNumber(), $customer->getSubscriptionStatus(), $customer->getName(), $vehicleDetails, $_POST['description']);
      $waitlist->writeToDB();

      // Redirect to status page
      header('Location:./status.php?caseid=' . $waitlist->getCaseID());
    }
    else
    {
      // Draw error message
      echo '<head>
                <!-- Include Boostrap (make the site look pretty) -->
                <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            
                <!-- Poppins font family -->
                <link href=\'https://fonts.googleapis.com/css?family=Poppins\' rel=\'stylesheet\'>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">
            
                <!-- Custom stylesheets -->
                <link rel="stylesheet" type="text/css" href="common.css">
                <link rel="stylesheet" type="text/css" href="redirect.css">
            </head>
            <body>
                <img id="logo" src="images/logo.png"/>
                <div class="container-fluid">
                    <div class="col-12" id="outer">
                        <h1 id="message">There was an error.<br/><br/>Click <a href=\'home.php\'>here</a> to return to the home page.</h1>
                    </div>
                </div>
            </body>';
    }
