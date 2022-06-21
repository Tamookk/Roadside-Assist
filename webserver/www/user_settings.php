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
    <div>
        <img id="logo" src="images/logo.png"/>
        <div class="container-fluid">
            <div class="col-12" id="outer">
        <?php
        include "../src/Customer.php";
        include "../src/Professional.php";

        $flag = 0;
        $customer = null;
        $professional = null;

        // Check to see if customer is logged in
        if (isset($_COOKIE['customer']))
        {
            $flag = 1;
            $cookie_data = $_COOKIE['customer'];
            $cookie_data = explode('|', $cookie_data);

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
        else
        {
            echo '<div id="error_message">
                <h2 class="error_message">Not logged in.</h2>
                <br/>
                <h2 class="error_message"><a href="/index.php">Return to Login</a></h2>
               </div>
            </div>
        </div>';
        }

        echo '<div id="content">
                    <div class="row">
                        <div class="col-12">';

        // Customer page
        if ($flag == 1)
        {
            echo '        <h1 id="welcome_message">Customer User Settings for ' . $customer->getName() . '</h1>
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
                        <div class="col-1"></div>
                        <div class="col-5" id="settings">
                            <h2 class="section_heading">Update Personal Details</h2>
                            <form action="user_settings_redirect.php" method="post" id="user_settings_form">
                                <table id="form-table">
                                    <tr>
                                        <td>
                                            Email
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="disabled_input" type="text" name="username" value="' . $customer->getEmail() . '" readonly/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br/>Phone Number
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="standard_input" type="text" name="phone" value="' . $customer->getPhoneNumber() . '"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br/>Address
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="standard_input" type="text" name="address" value="' . $customer->getLocation() . '"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><br/>Subscribed</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="checkbox" name="subscribed" ' . $customer->getSubscriptionStatus() . '/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br/>
                                            <input class="submit_button" type="submit"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>';
        }
        else if ($flag == 2)
        {
            echo '<h1 id="welcome_message">Professional User Settings for ' . $professional->getName() . '</h1>
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
                    <div class="col-1"></div>
                        <div class="col-5" id="settings">
                            <h2 class="section_heading">Update Personal Details</h2>
                            <form action="user_settings_redirect.php" method="post" id="user_settings_form">
                                <table id="form-table">
                                    <tr>
                                        <td>
                                            Email
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="disabled_input" type="text" name="username" value="' . $professional->getEmail() . '" readonly/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br/>Phone Number
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="standard_input" type="text" name="phone" value="' . $professional->getPhoneNumber() . '"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br/>Address
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="standard_input" type="text" name="address" value="' . $professional->getLocation() . '"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><br/>Business Name</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" class="standard_input" name="business_name" value="' . $professional->getBusinessName() . '"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br/>
                                            <input class="submit_button" type="submit"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>';
        }

        if ($flag != 0)
        {
            // Draw generate report div
            echo '<div class="col-1"></div>
              <div class="col-3" id="settings">
                <h2 class="section_heading">Report Generation</h2>
                  <form action="report_view.php" method="post" id="report_form">
                        <select id="report_type" name="report_type">
                            <option value="1">Review</option>
                            <option value="2">Repair</option>
                            <option value="3">Payment</option>
                        </select>
                        <br/><br/>
                        <input class="submit_button" type="submit" value="Generate Report"/>
                  </form>
                <br/>';
        }
        ?>
                </div>
            <div class="col-1"></div>
            </div>
        </div>
    </div>
    </body>
    <style>
        #checkbox
        {
            transform: scale(2);
        }

        .section_heading
        {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 2em;
            line-height: 1.5em;
            text-align: center;
        }

        .standard_input
        {
            box-sizing: border-box;
            width: 80%;
            height: 11.1%;
            background: #FFFFFF;
            border-radius: 50px;
            padding: 1%;
        }

        .disabled_input
        {
            box-sizing: border-box;
            width: 80%;
            height: 11.1%;
            background: #DDDDDD;
            border-radius: 50px;
            padding: 1%;
        }

        .submit_button
        {
            box-sizing: border-box;
            background: #FFFFFF;
            border-radius: 40px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.3em;
            line-height: 1em;
            color: #6C510B;
            height: 100%;
            padding: 1%;
        }

        .submit_button:hover
        {
            background: #DDDDDD;
        }

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

        #settings
        {
            background: #F5F5F5;
            border-radius: 100px;
            padding-bottom: 1%;
            border: solid 1px black;
            height: 60%;
        }

        #report_form
        {
            width: 100%;
            text-align: center;
        }

        select
        {
            box-sizing: border-box;
            width: 80%;
            height: 11.1%;
            background: #FFFFFF;
            border-radius: 50px;
            padding: 1%;
        }

        tbody
        {
            width: 100%;
        }

        td
        {
            width: 23.6%;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.25em;
            color: #6C510B;
            text-align: center;
        }
    </style>
</html>