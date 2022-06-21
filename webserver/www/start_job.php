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
include "../src/Vehicle.php";
include "../src/Waitlist.php";

// Flag for if customer logged in
$flag = 0;
$professional = null;

// Check for login cookie
if (!isset($_COOKIE['professional']))
{
    echo '<h1 id="message">Not logged in.
            <br/><br/>Click <a href=\'index.php\'>here</a> to return to login.
          </h1>
          </div>
          </div>';
    return;
}

// Get professional
$cookie_data = $_COOKIE['professional'];
$cookie_data = explode('|', $cookie_data);
$professional = new Professional($cookie_data[0], $cookie_data[1], 'professional');

// If professional does not exist, return
if ($professional->getID() == -1)
{
    echo '<h1 id="message">An error has occurred.
            <br/><br/>Click <a href=\'home.php\'>here</a> to return home.
          </h1>
          </div>
          </div>';
    return;
}

// Grab active case we are assigned to
$db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
$pdo = new \PDO("sqlite:" . $db_location);
$stmt = $pdo->prepare('SELECT CaseID from active where professional = :professional;');
$stmt->bindValue(':professional', $professional->getEmail());
$stmt->execute();
$activeCase = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$activeCase = mb_convert_encoding($activeCase, 'UTF-8', 'UTF-8');

// If there is no active case
if (count($activeCase) == 0)
{
    // Grab the oldest case in the waitlist
    $stmt = $pdo->prepare('SELECT CaseID from waiting order by CaseID ASC LIMIT 1');
    $stmt->execute();
    $lastUser = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $lastUser = mb_convert_encoding($lastUser, 'UTF-8', 'UTF-8');

    // If there is a user waiting...
    if (count($lastUser) > 0)
    {
        $waitlist = new Waitlist();
        $waitlist->pull($lastUser[0]['CaseID']);
        $waitlist->setToActive($waitlist->setDistance($professional->getLat(), $professional->getLong()), $professional->getEmail(), $professional->getBusinessName());
        $flag = 1;
    }
    else
    {
        $flag = 0;
    }
}
else
{
  $waitlist = new Waitlist();
  $waitlist->pull($activeCase[0]['CaseID']);
  $flag = 1;

  // If case ID is -1, there is nobody in the waitlist
  if ($waitlist->getCaseID() == -1)
  {
      echo '<h1 id="message">An error has occurred.
            <br/><br/>Click <a href=\'home.php\'>here</a> to return home.
          </h1>
          </div>
          </div>';
      return;
  }
}

// If flag not set, show an error and return
if ($flag == 0)
{
    echo '<h1 id="message">No cases are currently available.
            <br/><br/>Click <a href=\'home.php\'>here</a> to return home.
          </h1>
          </div>
          </div>';
    return;
}

// Draw page
echo '<div class="container-fluid">';

// We have a professional and a customer
if ($flag == 1)
{
    echo '<div id="content">
            <h1 id="welcome_message">Job Details</h1>
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
                <div class="col-3 settings">';

    echo '<h2 class="section_heading">Customer Information</h2>
                <table>
                    <tr>
                        <td><b>Case ID</b></td>
                        <td>' . $waitlist->getCaseID() . '</td>
                    </tr>
                    <tr>
                        <td><b>Name</b></td>
                        <td>' . $waitlist->getName() . '</td>
                    </tr>
                    <tr>
                        <td><b>Problem</b></td>
                        <td>' . $waitlist->getDescription() . '</td>
                    </tr>
                    <tr>
                        <td><b>Vehicle Details</b></td>
                        <td>' . $waitlist->getVehicle() . '</td>
                    </tr>
                    <tr>
                        <td><b>Registration</b></td>
                        <td>' . $waitlist->getRegistration() . '</td>
                    </tr>
                    <tr>
                        <td><b>Distance</b></td>
                        <td>' . $waitlist->getDistance() . ' KMs away</td>
                    </tr>
                </table>
            </div>
            <div class="col-1"></div>
            <!-- Temporary map, try dynamically create? -->
            <div class="col-5 settings">
                <h2 class="section_heading">Customer Location</h2>
                    <iframe src="https://maps.google.com/maps?q=' . $waitlist->getlat() . ',' . $waitlist->getLon() . '&basemap=satellite&hl=es;z=14&amp;output=embed"
                        width="600" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-1"></div>
        </div>
        <br/>
        <div class="row">
            <div class="col-12">
                <form action="review.php" method="post" id="job_done_button">
                    <input type="hidden" name="email_to" value="' . $waitlist->getUsername() . '">
                    <input type="hidden" name="caseid" value="' . $waitlist->getCaseID() . '">
                    <input type="submit" class="submit_button" value="Job Done?">
                </form>
            </div>
        </div>
        </div>
    </div>';
}
?>
</body>
<style>
    table
    {
        margin: 5%;
        background-color: white;
    }

    iframe
    {
        display: block;
        margin: auto;
        border-radius: 30px;
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
        text-align: center;
        display: block;
        margin: auto;
    }

    .submit_button:hover
    {
        background: #DDDDDD;
    }

    table
    {
        border: solid 1px black;
    }

    tbody
    {
        width: 100%;
    }

    td
    {
        border: solid 1px black;
        width: 23.6%;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 275;
        font-size: 1.25em;
        color: #6C510B;
        text-align: center;
    }

    .settings
    {
        background-color: #F5F5F5;
        border-radius: 100px;
        padding-bottom: 1%;
        padding-top: 1%;
        border: solid 1px black;
        height: 60%;
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
</style>
</html>
