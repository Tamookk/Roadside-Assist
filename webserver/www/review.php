<!-- Star rating code from: https://www.foolishdeveloper.com/2022/01/5-star-rating-html-css.html -->
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
        <div id="content">
<?php
include_once "../src/Customer.php";
include_once "../src/Professional.php";

// Flag for if customer or professional logged in
$flag = 0;
$customer = null;
$professional = null;
$email_from = '';

// Check for login cookie
if (isset($_COOKIE['customer']))
    $flag = 1;
else if (isset($_COOKIE['professional']))
    $flag = 2;
else
{
    echo '<div id="error_message">
                <h2 class="error_message">You are not logged in.</h2>
                <br/>
                <h2 class="error_message"><a href="/index.php">Return to Login</a></h2>
           </div>';
}

// Check for review POST data - if we don't have it, we don't have a valid account to leave a review for!
if ((!isset($_POST['email_to']) || !isset($_POST['caseid'])) && $flag != 0)
{
    echo '<div id="error_message">
                <h2 class="error_message">An error occurred.</h2>
                <br/>
                <h2 class="error_message"><a href="/home.php">Return to Home</a></h2>
           </div>';
    $flag = 0;
}

// Connect to user DB
$db_location = "../res/users.db";
$pdo = new \PDO("sqlite:" . $db_location);

// Get professional or customer, based upon who is leaving the review
if ($flag == 1)
{
    // Get customer
    $cookie_data = $_COOKIE['customer'];
    $cookie_data = explode('|', $cookie_data);
    $customer = new Customer($cookie_data[0], $cookie_data[1], 'customer');

    // If customer does not exist, return
    if ($customer->getID() == -1)
    {
        echo '<div id="error_message">
                <h2 class="error_message">An error occurred.</h2>
                <br/>
                <h2 class="error_message"><a href="/home.php">Return to Home</a></h2>
           </div>';
        $flag = 0;
    }

    if ($flag != 0)
    {
        // Set email from variable
        $email_from = $customer->getEmail();

        // Get professional we are leaving a review for
        $stmt = $pdo->prepare('SELECT * FROM Professional WHERE Email = :email;');
        $stmt->bindParam(":email", $_POST['email_to']);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, Professional database is not filled!
        if (count($data) < 1)
        {
            echo '<div id="error_message">
                <h2 class="error_message">An error occurred.</h2>
                <br/>
                <h2 class="error_message"><a href="/home.php">Return to Home</a></h2>
                </div>';
            $flag = 0;
        }

        if ($flag != 0)
        {
            // Create Professional
            $professional = new Professional($data[0]['Email'], $data[0]['Password'], "professional");

            if ($professional->getID() == -1)
            {
                echo '<div id="error_message">
                    <h2 class="error_message">An error occurred.</h2>
                    <br/>
                    <h2 class="error_message"><a href="/home.php">Return to Home</a></h2>
                    </div>';
                $flag = 0;
            }
        }
    }
}
else if ($flag == 2)
{
    // Get professional
    $cookie_data = $_COOKIE['professional'];
    $cookie_data = explode('|', $cookie_data);
    $professional = new Professional($cookie_data[0], $cookie_data[1], 'professional');

    // If customer does not exist, return
    if ($professional->getID() == -1)
    {
        echo '<div id="error_message">
                <h2 class="error_message">An error occurred.</h2>
                <br/>
                <h2 class="error_message"><a href="/home.php">Return to Home</a></h2>
           </div>';
        $flag = 0;
        return;
    }
    else
    {
        // Set email from variable
        $email_from = $professional->getEmail();

        // Get customer we are leaving a review for
        $stmt = $pdo->prepare('SELECT * FROM Customer WHERE Email = :email;');
        $stmt->bindParam(":email", $_POST['email_to']);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, Professional database is not filled!
        if (count($data) < 1)
        {
            echo '<div id="error_message">
                <h2 class="error_message">An error occurred.</h2>
                <br/>
                <h2 class="error_message"><a href="/home.php">Return to Home</a></h2>
                </div>';
            $flag = 0;
        }
    }

    if ($flag != 0)
    {
        // Create Customer
        $customer = new Customer($data[0]['Email'], $data[0]['Password'], "customer");
        if ($customer->getID() == -1)
        {
            echo '<div id="error_message">
                <h2 class="error_message">An error occurred.</h2>
                <br/>
                <h2 class="error_message"><a href="/home.php">Return to Home</a></h2>
                </div>';
            $flag = 0;
        }
    }
}


// Return if flag 0
if ($flag != 0)
{
    // Draw header
    echo '<h1 id="welcome_message">Leaving a Review for ';

    if ($flag == 1)
        echo 'Professional ' . $professional->getName();
    else if ($flag == 2)
        echo 'Customer ' . $customer->getName();
    else
        echo 'Nobody';

    echo '</h1>';

    // Draw menu bar
    echo '<br/>
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
                <div class="col-11" id="settings">';

    // Draw review form
    echo '
    <form action="review_redirect.php" method="post">
        <!-- Hidden inputs to pass through email address and user type of user we are leaving review for -->
        <input type="hidden" name="caseid" value="' . $_POST['caseid'] . '" hidden>
        <input type="text" name="email_from" value="' . $email_from . '" hidden>
        <input type="text" name="email_to" value="' . $_POST['email_to'] . '" hidden>
        <div id="table_outer">
            <table id="rating_table">
                <tr>
                    <td colspan="2">Rating</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="rating">
                            <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                            <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                            <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                            <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                            <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br/>Review</td>
                </tr>
                <tr>
                    <td colspan="2"><input type="text" class="standard_input" name="review"><br/><br/></td>
                </tr>
                <tr>
                    <td class="button_cell">
                    <!-- TODO change redirect for skip! -->
                        <input type="button" id="back_button" onclick="document.location.href=\'close.php?caseid=' . $_POST['caseid'] . '\'" value="Skip">
                        <br/><br/>
                    </td>
                    <td class="button_cell">
                        <!-- Hidden input with case ID -->
                        <input type="text" id="case_id" value="' . $_POST['caseid'] . '" hidden>
                        <input type="submit" id="submit_button" class="standard_input" value="Submit Review">
                        <br/><br/>
                    </td>
                </tr>
            <br/>
            </table>
        </div>
    </form>
    </div>';
}
?>
        </div>
    </div>
</div>
</body>
<style>
    .button_cell
    {
        width: 50%;
    }

    #back_button
    {
        color: #000000;
        width: 80%;
        border-radius: 50px;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 275;
        font-size: 1em; !important;
        background-color: #FF0000;
    }

    #back_button:hover
    {
        background-color: #DD0000;
    }

    #submit_button
    {
        color: #000000;
        width: 80%;
        border-radius: 50px;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 275;
        font-size: 1em;
        background-color: #00DD00;
    }

    #submit_button:hover
    {
        background-color: #00AA00;
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

    .standard_input
    {
        box-sizing: border-box;
        width: 80%;
        height: 11.1%;
        background: #FFFFFF;
        border-radius: 50px;
        padding: 1%;
    }

    #table_outer
    {
        display: block;
        margin: auto;
        background: #F5F5F5;
        border-radius: 100px;
        padding-bottom: 2%;
        padding-top: 1%;
        width: 70%;
    }

    #rating_table
    {
        margin-left: 5%;
        background-color: white;
        border-radius: 40px;
        width: 90%;
        font-family: 'Poppins';
        font-style: normal;
        font-size: 1.5em;
        line-height: 2em;
        text-align: center;
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

    .rating
    {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .rating > input
    {
        display:none;
    }

    .rating > label
    {
        position: relative;
        width: 1.1em;
        font-size: 3em;
        color: #FFD700;
        cursor: pointer;
    }

    .rating > label::before
    {
        content: "\2605";
        position: absolute;
        opacity: 0;
    }

    .rating > label:hover:before,
    .rating > label:hover ~ label:before
    {
        opacity: 1 !important;
    }

    .rating > input:checked ~ label:before
    {
        opacity:1;
    }

    .rating:hover > input:checked ~ label:before
    {
        opacity: 0.4;
    }
</style>
</html>
