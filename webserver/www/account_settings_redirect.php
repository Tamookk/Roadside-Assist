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
    <link rel="stylesheet" type="text/css" href="redirect.css">
</head>
<body>
<!-- Page content -->
<?php
include "../src/Customer.php";
include "../src/Professional.php";

// Flag for storing update status
$flag = 0;

// Check customer logged in
if (isset($_COOKIE['customer']))
    $flag = 1;

if ($flag == 1)
{
// List of data to check for
    $data = ['registration', 'make', 'model', 'condition', 'year'];

    for ($i = 0; $i < count($data); $i++) {
        // If any field is not set, return
        if (!isset($_POST[$data[$i]]))
            $flag = 0;
    }
}

if ($flag == 1)
{
    // Connect to database
    $db_location = str_ends_with(getcwd(), 'www') ? "../res/vehicle.db" : "webserver/res/vehicle.db";
    $pdo = new \PDO("sqlite:" . $db_location);

    // Prepare statement
    $stmt = $pdo->prepare("UPDATE Vehicle SET Make = :make, Model = :model, Condition = :condition, Year = :year WHERE Registration = :registration;");
    $stmt->bindValue(':make', $_POST['make']);
    $stmt->bindValue(':model', $_POST['model']);
    $stmt->bindValue(':condition', $_POST['condition']);
    $stmt->bindValue(':year', $_POST['year']);
    $stmt->bindValue(':registration', $_POST['registration']);

    // Try execute statement
    try
    {
        $stmt->execute();
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
        $flag = 0;
    }
}
?>
<img id="logo" src="images/logo.png"/>
<div class="container-fluid">
    <div class="col-12" id="outer">
        <h1 id="message"></h1>
    </div>
    <script>
        // Get status flag
        var status = Number.parseInt(<?php echo $flag; ?>);
        var homeLink = "<br/><br/>If you are not automatically redirected, click <a href='home.php'>here</a> to return to the home page.";

        // If status 0, there was an error. Show error message and redirect after 3 seconds.
        if (status == 0)
        {
            document.getElementById('message').innerHTML =
                "There was an error. Please wait while we return you to the home page." + homeLink;

            setTimeout(function() {
                window.location.href = "home.php";
            }, 3000);
        }
        else
        {
            // Display success message, then redirect to the home page after 3 seconds
            document.getElementById('message').innerHTML =
                "Success! Redirecting you to the home page. Please wait..." + homeLink;

            setTimeout(function() {
                window.location.href = "home.php";
            }, 3000);
        }
    </script>
</div>
</body>
</html>