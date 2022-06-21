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
// Flag for storing review status
$flag = 1;

// Check for review data
$data = ['email_from', 'email_to', 'rating', 'review', 'caseid'];
for ($i = 0; $i < count($data); $i++)
{
    // Make sure fields are set
    if (!isset($_POST[$data[$i]]))
        $flag = 0;
}

if ($flag == 1)
{
    // Connect to database, leave review
    $db_location = str_ends_with(getcwd(), 'www') ? "../res/review.db" : "webserver/res/review.db";
    $pdo = new \PDO("sqlite:" . $db_location);
    $date_now = date('Y-m-d');

    try
    {
        $stmt = $pdo->prepare("INSERT INTO Review ('ReviewFrom', 'ReviewTo', 'Date', 'Rating', 'Review') VALUES (:review_from, :review_to, :date, :rating, :review);");
        $stmt->bindParam(':review_from', $_POST['email_from']);
        $stmt->bindParam(':review_to', $_POST['email_to']);
        $stmt->bindParam(':date', $date_now);
        $stmt->bindParam(':rating', $_POST['rating']);
        $stmt->bindParam(':review', $_POST['review']);
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
        <script>
            // Get status flag
            var status = Number.parseInt(<?php echo $flag; ?>);
            var caseID = Number.parseInt(<?php echo $_POST['caseid']; ?>);
            var homeLink = "<br/><br/>If you are not automatically redirected, click <a href='close.php?caseid="
                + caseID + "'>here</a> to return to the home page.";

            // If status 0, there was an error. Show error message and redirect after 3 seconds.
            if (status == 0)
            {
                document.getElementById('message').innerHTML =
                    "There was an error. Please wait while we return you to the home page." + homeLink;

                setTimeout(function() {
                    window.location.href = "close.php?caseid='" + caseID;
                }, 3000);
            }
            else
            {
                // Display success message, then redirect to the home page after 3 seconds
                document.getElementById('message').innerHTML =
                    "Success! Redirecting you to the home page. Please wait..." + homeLink;

                setTimeout(function() {
                    window.location.href = "close.php?caseid=" + caseID;
                }, 3000);
            }
        </script>
    </div>
</div>
</body>
</html>
