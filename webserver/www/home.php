<!DOCTYPE html>
<html>
    <head>
        <!-- Include Boostrap (make the site look pretty) -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Poppins font family -->
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">

        <!-- Custom stylesheets -->
        <link rel="stylesheet" type="text/css" href="common.css">
    </head>
    <body>
    <?php
        include "../src/Customer.php";
        include "../src/Professional.php";
        include "../src/Vehicle.php";

        // Flag for if log in was successful or not, and customer object
        $flag = 0;
        $customer = null;
        $professional = null;

        // Check to see if customer is logged in
        if (isset($_COOKIE['customer']))
        {
            $flag = 1;
            $cookie_data = $_COOKIE['customer'];
            $cookie_data = explode('|', $cookie_data);

            // Create customer and vehicle objects
            $customer = new Customer($cookie_data[0], $cookie_data[1], 'customer');
        }
        // Check to see if professional is logged in
        else if (isset($_COOKIE['professional']))
        {
            $flag = 2;
            $cookie_data = $_COOKIE['professional'];
            $cookie_data = explode('|', $cookie_data);

            $professional = new Professional($cookie_data[0], $cookie_data[1], 'professional');
        }
        // Check to see if a username and password has been provided.
        else if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['user_type']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user_type = $_POST['user_type'];

            // Try create a user of type specified when logging in
            if ($user_type == 'customer')
            {
                $customer = new Customer($username, $password, $user_type);

                // If customer account ID is -1, customer does not exist
                if ($customer->getID() != -1)
                {
                    $flag = 1;

                    // Create cookie to store user data. Expires after 5 mins.
                    setcookie('customer', $username . '|' . $password, time() + 300, "/");
                }
            }
            else if ($user_type == 'professional')
            {
                $professional = new Professional($username, $password, $user_type);

                // If professional account ID is -1, professional does not exist
                if ($professional->getID() != -1)
                {
                    $flag = 2;

                    // Create cookie to store user data. Expires after 5 mins.
                    setcookie('professional', $username . '|' . $password, time() + 300, "/");
                }
            }
        }
    ?>

    <script>
        // Navigate to request help page when button clicked
        function requestHelpRedirect()
        {
            window.location.href = "request_help.php";
        }

        function statusRedirect(cid)
        {
            window.location.href = "status.php?caseid=" + cid;
        }

        // Navigate to provide help page when button clicked
        function startJobRedirect()
        {
            window.location.href = "start_job.php";
        }
    </script>

    <?php
    // Draw header
    echo '<img id="logo" src="images/logo.png"/>
            <div class="container-fluid">
            <div class="col-12" id="outer">';

    // Connect to waitlist database
    $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
    $pdo = new \PDO("sqlite:" . $db_location);

    // Show error message if not logged in
    if ($flag == 0)
    {
        echo '<div id="error_message">
                <h2 class="error_message">Invalid username and/or password.</h2>
                <br/>
                <h2 class="error_message"><a href="/index.php">Return to Login</a></h2>
               </div>
            </div>
        </div>';
    }

    // Show page based on customer type
    if ($flag == 1)
    {
        // Check for any active cases
        $stmt = $pdo->prepare('SELECT CaseID, emails FROM (select CaseID, email as emails from waiting union select CaseID, email from active) where emails = :email;');
        $stmt->bindValue(':email', $customer->getEmail());
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // Check for any customer vehicles
        $vehicle = new Vehicle($customer->getRegistration());

        // Draw customer page
        echo '<div id="content">
                <div class="row">
                    <div class="col-12">
                        <h1 id="welcome_message">Welcome, ' . $customer->getName() . '</h1>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-1">
                        <button class="menu_button" onclick=\'document.location.href="home.php"\'>Home</button>
                        <br/><br/>
                        <button class="menu_button" onclick=\'document.location.href="user_settings.php"\'>My Profile</button>
                        <br/><br/>
                        <button class="menu_button" onclick=\'document.location.href="account_settings.php"\'>Settings</button>
                        <br/><br/>
                        <button class="menu_button" id="logout_button" onclick=\'document.location.href="index.php?logout=1"\'>Log Out</button>
                    </div>
                    <div class="col-4">
                        <div id="case_status">
                            <h2 id="active_case">Active Case</h2>';

        // Echo correct data based on if we have a case or not
        if (count($data) > 0)
        {
            echo '<button id="case_button" onclick="statusRedirect(' . $data[0]['CaseID'] . ')">Check Current Case</button>';
        }
        else
        {
            echo '<button id="case_button" onclick="requestHelpRedirect()">Request Help</button>';
        }

        echo '</div>
              <br/>
              <div id="account_summary">
                <h2>Account Summary</h2>
                <div id="account_summary_text">
                    <b>Account Type</b>: ';

        // Echo subscription status
        if ($customer->getSubscriptionStatus() == 'checked')
            echo 'Subscriber';
        else
            echo 'Pay As You Go';

        echo '<br/>
                <b>Email</b>: ' . $customer->getEmail() . '
                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="container-fluid">';

        // Draw vehicle data if we have any
        if ($vehicle->getRegistration() != -1)
        {
            echo '<div class="vehicle row">
                    <h2>Vehicle</h2>
                    <div class="col-4 vehicle_image">
                        <img class="vehicle_image_content" src="https://imageio.forbes.com/specials-images/imageserve/5d35eacaf1176b0008974b54/0x0.jpg?format=jpg&crop=4560,2565,x790,y784,safe&width=1200">
                    </div>
                    <div class="col-1"></div>
                    <div class="col-7 vehicle_description">
                        <b>Registration</b>: ' . $vehicle->getRegistration() .  '
                        <br/>
                        <b>Make</b>: ' . $vehicle->getMake() .  '
                        <br/>
                        <b>Model</b>: ' . $vehicle->getModel() .  '
                        <br/>
                        <b>Year</b>: ' . $vehicle->getYear() .  '
                        <br/>
                        <b>Condition</b>: ' . $vehicle->getCondition() .  '
                    </div>
                    <br/>
                    <br/>
                  </div>
                  <br/>';
        }
        // Otherwise draw add vehicle button
        else
        {
            echo '          <div class="vehicle row">
                                <h2>Add Vehicle</h2>
                                <button onclick=\'document.location.href="account_settings.php"\' id="add_vehicle_button">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/>';
        }
    }
    else if($flag == 2)
    {
        $stmt = $pdo->prepare('SELECT * FROM active WHERE professional = "' . $professional->getEmail() . '";');
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // Draw professional page
        echo '<div id="content">
                    <div class="row">
                        <div class="col-12">
                            <h1 id="welcome_message">Welcome, ' . $professional->getName() . '</h1>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-1">
                            <button class="menu_button" onclick=\'document.location.href="home.php"\'>Home</button>
                            <br/><br/>
                            <button class="menu_button" onclick=\'document.location.href="user_settings.php"\'>My Profile</button>
                            <br/><br/>
                            <button class="menu_button" onclick=\'document.location.href="account_settings.php"\'>Settings</button>
                            <br/><br/>
                            <button class="menu_button" id="logout_button" onclick=\'document.location.href="index.php?logout=1"\'>Log Out</button>
                        </div>
                        <div class="col-4">
                            <div id="case_status">';

        // Echo correct data based on if we have a case or not
        if (count($data) > 0)
        {
            echo '<h2 id="active_case">One Active Case</h2>';
            echo '<button id="case_button" onclick="startJobRedirect()">Check Current Job</button>';
        }
        else
        {
            echo '<h2 id="active_case">No Active Cases</h2>';
            echo '<button id="case_button" onclick="startJobRedirect()">Start Taking Jobs</button>';
        }

        echo '</div>
                </div>
                  <div class="col-6" id="account_summary_pro">
                    <h2>Account Summary</h2>
                    <div id="account_summary_text">
                        <b>Business Name</b>: ' . $professional->getBusinessName() . '<br/>
                        <b>Email</b>: ' . $professional->getEmail() . '
                        <br/>
                    </div>
                  </div>
                </div>
                </div>
                </div>
                </div>
                </div>';
    }

    ?>
    </body>
    <style>
        .error_message
        {
            text-align: center;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2em;
            line-height: 1.5em;
        }

        #error_message
        {
            width: 80%;
            position: absolute;
            top: 20%;
            left: 10%;
        }

        #case_button
        {
            width: 80%;
            background: #FFFFFF;
            border-radius: 50px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2em;
            line-height: 1.5em;
            color: #000000;
            display: block;
            margin: auto;
        }

        #case_button:hover
        {
            background-color: #DDDDDD;
        }

        #case_status
        {
            border: solid 1px black;
            box-sizing: border-box;
            background: #F5F5F5;
            border-radius: 50px;
            padding: 0% 1% 5% 1%;
        }

        #active_case
        {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2.5em;
            line-height: 2em;
            color: #000000;
            text-align: center;
        }

        .case_log
        {
            display: block;
            padding: 2%;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.5em;
            line-height: 1em;
            color: #000000;
            background: #FFFFFF;
            border-radius: 30px;
            width: 95%;
            margin: auto;
        }

        .vehicle
        {
            border: solid 1px black;
            box-sizing: border-box;
            background-color: #F5F5F5;
            border-radius: 50px;
            width: 100%;
            text-align: center;
            height: 20%;
            padding: 0% 1% 1% 1%;
        }

        .vehicle>h2
        {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2.5em;
            line-height: 2em;
            color: #000000;
        }

        .vehicle_image
        {
            border-radius: 40px;
            background-color: white;
            padding: 2%;
            height: 5%;
        }

        .vehicle_image_content
        {
            height: 100%;
            width: 100%;
        }

        .vehicle_description
        {
            background-color: #FFFFFF;
            border-radius: 50px;
            font-family: 'Poppins';
            font-style: normal;
            font-size: 1.5em;
            line-height: 1.2em;
            color: #000000;
            text-align: left;
            padding: 2%;
        }

        #add_vehicle
        {
            border: solid 1px black;
            box-sizing: border-box;
            background-color: #F5F5F5;
            border-radius: 50px;
            text-align: center;
            height: 20%;
            width: 100%;
            padding: 0% 1% 1% 1%;
            border: solid 1px black;
        }

        #add_vehicle > h2
        {
            font-family: 'Poppins';
            font-weight: 275;
            font-size: 2.5em;
            line-height: 2em;
            color: #000000;
        }

        #add_vehicle_button
        {
            width: 30%;
            height: 50%;
            background: #FFFFFF;
            border-radius: 50px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2.5em;
            line-height: 2em;
            color: #000000;
            display: block;
            margin: auto;
        }

        #add_vehicle_button:hover
        {
            background-color: #BBBBBB;
        }

        #account_summary
        {
            width: 100%;
            box-sizing: border-box;
            background: #F5F5F5;
            border-radius: 50px;

            border: solid 1px black;
            color: #000000;
            padding: 0% 1% 5% 1%;
        }

        #account_summary_pro
        {
            box-sizing: border-box;
            background: #F5F5F5;
            border-radius: 50px;
            border: solid 1px black;
            color: #000000;
        }

        #account_summary > h2
        {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2.5em;
            line-height: 2em;
            text-align: center;
        }

        #account_summary_pro > h2
        {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2.5em;
            line-height: 2em;
            text-align: center;
        }

        #account_summary_text
        {
            background: #FFFFFF;
            border-radius: 30px;
            font-style: normal;
            color: #000000;
            font-family: 'Poppins';
            font-size: 1.5em;
            line-height: 1.2em;
            text-align: left;
            padding: 2%;
        }

        .menu_button
        {
            box-sizing: border-box;
            background-color: #FFE67A;
            border-radius: 40px;
            height: auto;
            width: 100%;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.5em;
            line-height: 1.5em;
            text-align: center;
            color: #000000;
        }

        #logout_button
        {
            background-color: #FABC32;
        }

        .menu_button:hover
        {
            background-color: #958315;
        }

        #logout_button:hover
        {
            background-color: #AD7700;
        }
    </style>
</html>
