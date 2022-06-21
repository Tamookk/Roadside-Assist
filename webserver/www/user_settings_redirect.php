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

// List of data to check for
$data = ['username', 'phone', 'address'];

for ($i = 0; $i < count($data); $i++)
{
    // If any field is not set, return
    if (!isset($_POST[$data[$i]]))
        return;
}

// Connect to database
$db_location = str_ends_with(getcwd(), 'www') ? "../res/users.db" : "webserver/res/users.db";
$pdo = new \PDO("sqlite:" . $db_location);

// Get user we are updating
if (isset($_COOKIE['customer']))
{
    // Get subscription status - if it exists, it is checked. If it does not exist, it is not checked.
    if (isset($_POST['subscribed']))
        $subscription_status = true;
    else
        $subscription_status = false;

    // Prepare statement
    $stmt = $pdo->prepare("UPDATE Customer SET Phone = :phone, Subscribed = :subscribed WHERE Email = :email;");
    $stmt->bindValue(':phone', $_POST['phone']);
    $stmt->bindValue(':subscribed', $subscription_status);
    $stmt->bindValue(':email', $_POST['username']);
    $flag = 1;
}
else if (isset($_COOKIE['professional']))
{
    // Get business name
    $business_name = $_POST['business_name'];

    // Prepare statement
    $stmt = $pdo->prepare("UPDATE Professional SET Phone = :phone, BusinessName = :business_name WHERE Email = :email;");
    $stmt->bindValue(':phone', $_POST['phone']);
    $stmt->bindValue(':business_name', $business_name);
    $stmt->bindValue(':email', $_POST['username']);
    $flag = 1;
}

// Try update
if ($flag == 1)
{
    try
    {
        $stmt->execute();
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
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