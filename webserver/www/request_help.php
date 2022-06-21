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
        <link rel="stylesheet" type="text/css" href="menu_buttons.css">
        <link rel="stylesheet" type="text/css" href="redirect.css">
    </head>
    <body>
        <img id="logo" src="images/logo.png"/>
        <div class="container-fluid">
            <div class="col-12" id="outer">
    <?php
    {
        include "../src/Customer.php";
        include "../src/Vehicle.php";
        include "../src/Waitlist.php";

        // Flag for if customer logged in
        $flag = 0;
        $customer = null;
        $vehicle = null;

        // Check for login cookie
        if (!isset($_COOKIE['customer']))
        {
            echo '<h1 id="message">Not logged in.
                    <br/><br/>Click <a href=\'index.php\'>here</a> to return to the home page.
                    </h1>
                </div>
                </div>
                </body>';
            return;
        }

        // Get customer
        $cookie_data = $_COOKIE['customer'];
        $cookie_data = explode('|', $cookie_data);
        $customer = new Customer($cookie_data[0], $cookie_data[1], 'customer');
        $vehicle = new Vehicle($customer->getRegistration());

        // If customer does not exist or does not have a vehicle, return
        if ($customer->getID() == -1 || $vehicle->getRegistration() == -1)
        {
            echo '<h1 id="message">An error has occurred.
                    <br/><br/>Click <a href=\'index.php\'>here</a> to return to the home page.
                    </h1>
                </div>
                </div>
                </body>';
            return;
        }

        // Set flag based on customer subscription status (1 if subscribed, 2 if not)
        $flag = $customer->getSubscriptionStatus() == 'checked' ? 1 : 2;
    }

    echo '<div id="content">
                <h1 id="welcome_message">Job Confirmation</h1>
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
                    <div class="col-11">
                        <table id="detail_table">
                            <tr>
                                <td class="left_data"><b>Driver Name<b></td>
                                <td class="right_data">' . $customer->getName() . '</td>
                            </tr>
                            <tr>
                                <td class="left_data"><b>Driver Phone Number</b></td>
                                <td class="right_data">' . $customer->getPhoneNumber() . '</td>
                            </tr>
                            <tr>
                                <td class="left_data"><b>Vehicle Registration</b></td>
                                <td class="right_data">' . $customer->getRegistration() . '</td>
                            </tr>
                            <tr>
                                <td class="left_data"><b>Vehicle Description</b></td>
                                <td class="right_data">' . $vehicle->getYear() . ' ' .  $vehicle->getMake() . ' ' . $vehicle->getModel() . '</td>
                            </tr>
                            <tr>
                                <td class="left_data"><b>Incident Location</b></td>
                                <td class="right_data">' . $customer->getLocation() . '</td>
                            </tr>';

    // If customer not subscriber
    if ($flag == 2)
    {
      echo '<tr>
                <td class="left_data"><b>Cost</b></td>
                <td class="right_data">$200</td>
            </tr>';
    }

      echo '                <tr>
                                <td colspan="2" style="text-align:center"><b>Incident Description</b></td>
                            </tr>
                        </table>
                        <form action="/Request.php" method="post">
                          <textarea id="problem_area" rows="4" name="description">Broken Tire?</textarea>
                          <br><br>
                          <table id="button_table">
                            <tr>
                                <td><input type="button" onclick="history.back();" id="back_button" value="Back"></td>
                                <td><input type="submit" id="submit_button" value="Submit"></td>
                            </tr>
                          </table>
                        </form>
                    </div>
                </div>
            </div>';
    ?>
            </div>
        </div>
    </body>
    <style>
        #back_button
        {
            color: #000000;
            width: 25%;
            border-radius: 50px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.3em;
            line-height: 2em;
            background-color: #FF0000;
        }

        #back_button:hover
        {
            background-color: #DD0000;
        }

        #submit_button
        {
            color: #000000;
            width: 25%;
            border-radius: 50px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.3em;
            line-height: 2em;
            background-color: #00DD00;
        }

        #submit_button:hover
        {
            background-color: #00AA00;
        }

        #button_table
        {
            width: 60%;
            margin-left: 33%;
            font-family: 'Poppins';
            font-style: normal;
            font-size: 1em;
            line-height: 2em;
        }

        #problem_area
        {
            position: relative;
            left:35%;
            width: 30%;
            text-align:center;
            font-family: 'Poppins';
            font-style: normal;
            font-size: 1em;
            line-height: 2em;
            resize: none;
        }

        #detail_table
        {
            width: 40%;
            margin-left: 30%;
            font-family: 'Poppins';
            font-style: normal;
            font-size: 1em;
            line-height: 2em;
        }

        td
        {
            padding: 1%;
        }

        .left_data
        {
            text-align: right;
        }

        .right_data
        {
            text-align: left;
        }
    </style>
</html>
