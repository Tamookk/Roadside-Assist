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
include "../src/Customer.php";
include "../src/Professional.php";

$flag = 0;
$report_type = 0;
$user_type = 0;
$user = null;

// Check to see if customer is logged in
if (isset($_COOKIE['customer']))
{
    $user_type = 1;
    $cookie_data = $_COOKIE['customer'];
    $cookie_data = explode('|', $cookie_data);

    $user = new Customer($cookie_data[0], $cookie_data[1], 'customer');
}
// Check to see if professional is logged in
else if (isset($_COOKIE['professional']))
{
    $user_type = 2;
    $cookie_data = $_COOKIE['professional'];
    $cookie_data = explode('|', $cookie_data);

    $user = new Professional($cookie_data[0], $cookie_data[1], 'professional');
}

// Check for POST specifying type of report
if (!isset($_POST['report_type']))
    $flag = -1;
else
    $report_type = $_POST['report_type'];

// Return home if nobody logged in or no report type specified
if ($user_type == 0 || $flag == -1 || $report_type == 0)
{
    echo '<h1 id="message">Not logged in, or report type not specified.
            <br/><br/>Click <a href=\'home.php\'>here</a> to return to the home page.
          </h1>';
    return;
}

// Generate review report
if ($report_type == 1)
{
    // Get report data
    $report_data = Report::generateReviewReport($user->getEmail());

    // If report data does not exist, return
    if ($report_data == 0)
    {
        echo '<h1 id="message">No review data for this user.</h1>';
        echo '<br/><br/>Click <a href=\'home.php\'>here</a> to return to the home page.';
        return;
    }

    echo '<div id="content">
            <h1 id="welcome_message">Review Report for ' . $user->getName() . '</h1>
            <br/>
            <div class="row">
                <div class="col-1">
                    <button class="menu_button" onclick=\'document.location.href="home.php"\'>Home</button>
                    <br/>
                    <button class="menu_button" onclick=\'document.location.href="user_settings.php"\'>My Profile</button>
                    <br/>
                    <button class="menu_button" onclick=\'document.location.href="account_settings.php"\'>Settings</button>
                    <br/>
                    <button class="menu_button" id="logout_button" onclick=\'document.location.href="index.php?logout=1"\'>Log Out</button>
                </div>
                <div class="col-11">
                <div id="table_outer">
                    <table id="report_table">
                    <tr>
                        <th>Date</th>
                        <th>Rating</th>
                        <th>Review</th>
                    </tr>';

    // Generate report page
    foreach ($report_data as $report)
    {
        echo '
            <tr>
                <td>' . $report[0] . '</td>
                <td>' . $report[1] . '</td>
                <td>' . $report[2] . '</td>
            </tr>';
    }

    echo '</table>
          </div>
          </div>
          </div>
          <br/>';
}
// Generate repair report
if ($report_type == 2)
{
    // Get report data
    $report_data = Report::generateRepairReport($user->getEmail());

    // If report data does not exist, return
    if ($report_data == 0)
    {
        echo '<h1 id="message">No repair data for this user.</h1>';
        echo '<br/><br/>Click <a href=\'home.php\'>here</a> to return to the home page.';
        return;
    }

    echo '<div id="content">
            <h1 id="welcome_message">Repair Report for ' . $user->getName() . '</h1>
            <br/>
            <div class="row">
                <div class="col-1">
                    <button class="menu_button" onclick=\'document.location.href="home.php"\'>Home</button>
                    <br/>
                    <button class="menu_button" onclick=\'document.location.href="user_settings.php"\'>My Profile</button>
                    <br/>
                    <button class="menu_button" onclick=\'document.location.href="account_settings.php"\'>Settings</button>
                    <br/>
                    <button class="menu_button" id="logout_button" onclick=\'document.location.href="index.php?logout=1"\'>Log Out</button>
                </div>
                <div class="col-11">
                <div id="table_outer">
                    <table id="report_table">
                    <tr>
                        <th>Date</th>
                        <th>Attending Professional</th>
                        <th>Location</th>
                        <th>Vehicle Details</th>
                    </tr>';

    // Generate report page
    foreach ($report_data as $report)
    {
        echo '
            <tr>
                <td>' . $report[0] . '</td>
                <td>' . $report[1] . '</td>
                <td>' . $report[2] . '</td>
                <td>' . $report[3] . '</td>
            </tr>';
    }

    echo '</table>
          </div>
          </div>
          </div>
          <br/>';
}
// Generate payment report
if ($report_type == 3)
{
    // Get report data
    $report_data = Report::generatePaymentReport($user->getEmail());

    // If report data does not exist, return
    if ($report_data == 0)
    {
        echo '<h1 id="message">No payment data for this user.</h1>';
        echo '<br/><br/>Click <a href=\'home.php\'>here</a> to return to the home page.';
        return;
    }

    echo '<div id="content">
            <h1 id="welcome_message">Payment Report for ' . $user->getName() . '</h1>
            <br/>
            <div class="row">
                <div class="col-1">
                    <button class="menu_button" onclick=\'document.location.href="home.php"\'>Home</button>
                    <br/>
                    <button class="menu_button" onclick=\'document.location.href="user_settings.php"\'>My Profile</button>
                    <br/>
                    <button class="menu_button" onclick=\'document.location.href="account_settings.php"\'>Settings</button>
                    <br/>
                    <button class="menu_button" id="logout_button" onclick=\'document.location.href="index.php?logout=1"\'>Log Out</button>
                </div>
                <div class="col-11">
                <div id="table_outer">
                    <table id="report_table">
                    <tr>
                        <th>Date</th>
                        <th>Payee</th>
                        <th>Amount</th>
                    </tr>';

    // Generate report page
    foreach ($report_data as $report)
    {
        echo '
            <tr>
                <td>' . $report[0] . '</td>
                <td>' . $report[1] . '</td>
                <td>' . $report[2] . '</td>
            </tr>';
    }

    echo '</table>
          </div>
          </div>
          </div>
          <br/>';
}

// Draw print report button
echo '<button id="submit_button" onclick="window.print();">Print this Page</button>
<br/></div>';
?>
    </div>
</div>
</body>
<style>
    #table_outer
    {
        display: block;
        margin: auto;
        background: #F5F5F5;
        border-radius: 100px;
        padding-bottom: 1%;
        padding-top: 1%;
        width: 70%;
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
        line-height: 1.5em;
        color: #6C510B;
    }

    #submit_button:hover
    {
        background: #DDDDDD;
    }

    #report_table
    {
        margin-left: 5%;
        background-color: white;
        border: solid 1px black;
        width: 90%;
        font-family: 'Poppins';
        font-style: normal;
        font-size: 1em;
    }

    #report_table tr td:first-child, #report_table tr td:nth-child(2)
    {
        width: 10%;
    }

    #report_table td
    {
        border: solid 1px black;
    }

    #report_table th
    {
        border: solid 1px black;
        text-align: center;
    }

    #footer
    {
        background-color: gray; /* Temporary - remove or change once icon colors changed */
        text-align: center;
        width: 100%;
    }

    #footer td
    {
        border: solid 1px black;
        width: 25%; /* 1/4 of the table's width */
    }

    button
    {
        display: block;
        margin: auto;
    }

    /* For printing the report */
    @media print
    {
        #table_outer
        {
            width: 100%;
        }

        #outer
        {
            margin: auto;
        }

        #content
        {
            position: unset;
            margin: auto;
        }

        /* Adjust table size to better fit portrait page */
        #report_table
        {
            border: solid 1px black;
            margin: auto;
            width: 100%;
        }

        #report_table tr td:first-child, #report_table tr td:nth-child(2)
        {
            width: 20%;
        }

        /* Hide the footer */
        #footer
        {
            display: none;
        }

        /* Hide print button */
        button
        {
            display: none;
        }

        #logo
        {
            visibility: hidden;
        }

        #welcome_message
        {
            box-sizing: border-box;
            display: block;
            margin: auto;
            width: auto;
            background: #FFFFFF;
            border-radius: 0;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.5em;
            line-height: 1em;
            text-align: center;
            color: #000000;
            margin-left: auto;
        }
    }
</style>
</html>