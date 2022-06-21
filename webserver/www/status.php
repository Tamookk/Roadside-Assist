<!DOCTYPE html>
<html>
<head>
    <!-- Include Boostrap (make the site look pretty) -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Redirect to the review page when a job is done. TODO
        function jobDone()
        {
            alert("Job is done!");
            window.location.href = "home.php";
        }
    </script>

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
    include "../src/Waitlist.php";

    // Connect to waitlist database
    $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
    $pdo = new \PDO("sqlite:" . $db_location);

    // Get case ID from GET
    $caseID = intval($_GET['caseid']);

    // Find case with caseid from waiting table
    $stmt = $pdo->prepare("SELECT * FROM waiting WHERE CaseID = :case_id;");
    $stmt->bindParam(':case_id', $caseID);
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // Echo case status
    echo '<h1 id="welcome_message">Status of case: ' . $caseID . '</h1>';
?>
            <br/>
            <div class="row">
                <div class="col-1">
                    <button class="menu_button" onclick='document.location.href="home.php"'>Home</button>
                    <br/><br/>
                    <button class="menu_button" onclick='document.location.href="user_settings.php"'>My Profile</button>
                    <br/><br/>
                    <button class="menu_button" onclick='document.location.href="account_settings.php"'>Settings</button>
                    <br/><br/>
                    <button class="menu_button" id="logout_button" onclick='document.location.href="index.php?logout=1"'>Log Out</button>
                </div>
                <div class="col-11">
<?php
    // If we are waiting for a professional...
    if (count($data) == 1)
    {
        echo '<p id="p_message">Currently looking for a repair professional, please wait!</p>';
        echo '<button id="cancel_button" onclick="window.location.href=\'close.php?caseid=' . $caseID . '\'">';
        echo 'Cancel </button>';
    }
    // Otherwise we are active or closed
    else
    {
        // Check to see if we are active
        $stmt = $pdo->prepare('SELECT * FROM active WHERE CaseID = :case_id;');
        $stmt->bindParam(':case_id', $caseID);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $waitlist = new Waitlist();
        $waitlist->pull($caseID);

        // If we are active...
        if (count($data) == 1)
        {
            echo '<p id="p_message">A Repair Professional from ' . $waitlist->getCompany() . ' will be with you shortly!</p>';
            echo '<iframe style="display: block; left:50%" src="https://maps.google.com/maps?q=' . $waitlist->getlat() . ',' . $waitlist->getLon() . '&basemap=satellite&hl=es;z=14&amp;output=embed"
                        width="600" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        }
        // Otherwise, check to see if we are closed.
        else
        {
            $stmt = $pdo->prepare('SELECT * FROM closed WHERE CaseID = :case_id;');
            $stmt->bindParam(':case_id', $caseID);
            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // If we have data
            if (count($data) == 1)
            {
                // Get professional that served us TODO
                $stmt = $pdo->prepare('SELECT professional from closed where CaseID = :case_id;');
                $stmt->bindParam(':case_id', $caseID);
                $stmt->execute();
                $proUser =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $proUser = mb_convert_encoding($proUser, 'UTF-8', 'UTF-8');

                if (count($proUser) < 1)
                {
                    echo '<p id="p_message">This case has been resolved.</p><br/>';
                    echo '<form action="home.php" method="post" id="job_done_button">
                        <input type="submit" value="Return Home" id="review_button">
                      </form>';
                }
                else
                {
                    // Echo case resolved by professional,
                    $proUsername = $proUser[0]['professional'];
                    echo '<p id="p_message">This case has been resolved.</p><br/>';

                    // Echo hidden input with case ID and professional's username
                    echo '<form action="review.php" method="post" id="job_done_button">
                        <input type="text" name="email_to" value="' . $proUsername . '" hidden>
                        <input type="text" name="caseid" value="' . $caseID . '" hidden>
                        <input type="submit" value="Leave a review?" id="review_button">
                      </form>';
                }
            }
        }
    }
?>
<?php
    $url1=$_SERVER['REQUEST_URI'];
    header("Refresh: 15; URL=$url1");
?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<style>
    iframe
    {
        display: block;
        margin: auto;
        border-radius: 30px;
    }
    #review_button
    {
        width: 20%;
        box-sizing: border-box;
        background: #FFFFFF;
        border-radius: 40px;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 275;
        font-size: 2em;
        line-height: 1.5em;
        color: #6C510B;
        display: block;
        margin: auto;
    }

    #review_button:hover
    {
        background: #DDDDDD;
    }

    #p_message
    {
        font-family: 'Poppins';
        font-style: normal;
        font-size: 1.5em;
        text-align: center;
    }

    #cancel_button
    {
        color: #000000;
        width: 15%;
        display: block;
        margin: auto;
        border-radius: 50px;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 275;
        font-size: 1.3em;
        line-height: 2em;
        background-color: #FF0000;
    }

    #cancel_button:hover
    {
        background-color: #DD0000;
    }
</style>
</html>
