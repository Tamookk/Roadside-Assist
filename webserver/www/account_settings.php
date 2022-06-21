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
</head>
<body>
<?php
include "../src/Customer.php";
include "../src/Professional.php";
include "../src/Vehicle.php";

$flag = 0;
$customer = null;
$professional = null;
$vehicle = null;

// Check to see if customer is logged in
if (isset($_COOKIE['customer']))
{
    $flag = 1;
    $cookie_data = $_COOKIE['customer'];
    $cookie_data = explode('|', $cookie_data);

    // Create customer and vehicle objects
    $customer = new Customer($cookie_data[0], $cookie_data[1], 'customer');
    $vehicle = new Vehicle($customer->getRegistration());
}
else if (isset($_COOKIE['professional']))
{
    $flag = 2;
    $cookie_data = $_COOKIE['professional'];
    $cookie_data = explode('|', $cookie_data);

    $professional = new Professional($cookie_data[0], $cookie_data[1], 'professional');
}

// Draw header
echo '<img id="logo" src="images/logo.png"/>
        <div class="container-fluid">
            <div class="col-12" id="outer">';

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
else if ($flag == 1)
{
    echo '<div id="content">
            <div class="row">
                <div class="col-12">
                    <h1 id="welcome_message">Vehicle Settings for ' . $customer->getName() . '</h1>
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
                <div class="col-11" id="settings">
                    <form method="post" action="account_settings_redirect.php" id="account_settings_form"></form>
                    <table>
                        <tr>
                            <td>Registration</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="standard_input" id="readonly_input" name="registration" form="account_settings_form" value="' . $customer->getRegistration() . '" readonly/>
                            </td>
                        </tr>
                        <tr>
                            <td>Make</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="standard_input" name="make" form="account_settings_form" value="' . $vehicle->getMake() . '"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Model</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="standard_input" name="model" form="account_settings_form" value="' . $vehicle->getModel() . '"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Condition</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="standard_input" name="condition" form="account_settings_form" value="' . $vehicle->getCondition() . '"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Year</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="standard_input" name="year" form="account_settings_form" value="' . $vehicle->getYear() . '"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <br/>
                                <input type="submit" class="standard_input" id="submit_button" form="account_settings_form" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
          </div>
      </div>
    </div>';
}
else if ($flag == 2)
{
    echo '<div id="content">
            <div class="row">
                <div class="col-12">
                    <h1 id="welcome_message">Professional Account Settings for ' . $professional->getName() . '</h1>
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
                <div class="col-10">
                    <p>Coming soon...</p>
                </div>
            </div>
        </div>
    </div>
    </div>';
}
?>
</body>
<style>
    p
    {
        text-align: center;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 275;
        font-size: 2em;
        line-height: 1.5em;
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

    .standard_input
    {
        box-sizing: border-box;
        width: 80%;
        height: 11.1%;
        background: #FFFFFF;
        border-radius: 50px;
        padding: 1%;
    }

    #readonly_input
    {
        border: solid 1px gray;
    }

    #error_message
    {
        width: 80%;
        position: absolute;
        top: 20%;
        left: 10%;
    }

    #submit_button
    {
        box-sizing: border-box;
        background: #FFFFFF;
        border-radius: 40px;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 275;
        font-size: 1.5em;
        line-height: 1em;
        color: #6C510B;
        height: 100%;
    }

    #submit_button:hover
    {
        background: #DDDDDD;
    }

    #settings
    {
        display: block;
        margin: auto;
        width: 60%;
        background: #F5F5F5;
        border-radius: 100px;
        padding-bottom: 1%;
        border: solid 1px black;
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
        font-size: 1.5em;
        color: #6C510B;
        text-align: center;
    }
</style>
</html>