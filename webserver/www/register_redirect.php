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
        <link rel="stylesheet" type="text/css" href="redirect.css.css">
    </head>
    <body>
        <!-- Page content -->
        <?php
        include "../src/Customer.php";
        include "../src/Professional.php";
        include "../src/Vehicle.php";

        // Flag for storing registration status
        $flag = 1;

        // List of data to check for - does not check repeated password, as register form
        // should stop a user from continuing if passwords do not match.
        $data = ['first_name', 'last_name', 'email', 'phone', 'address', 'password', 'user_type'];

        for ($i = 0; $i < count($data); $i++)
        {
            // If any field is not set, return
            if (!isset($_POST[$data[$i]]))
                $flag = 0;
        }

        // Check if we are still good
        if ($flag == 1)
        {
            // Connect to database
            $db_location = str_ends_with(getcwd(), 'www') ? "../res/users.db" : "webserver/res/users.db";
            $pdo = new \PDO("sqlite:" . $db_location);

            // Try to create a user of type specified
            if ($_POST['user_type'] == 'customer')
            {
                // Create Customer object
                $customer = new Customer($_POST['email'], $_POST['password'], 'customer');

                // If ID is not -1, customer already exists
                if ($customer->getID() != -1)
                    $flag = 0;

                // Check if we are still good
                if ($flag == 1)
                {
                    // Prepare SQL statement
                    $stmt = $pdo->prepare("INSERT INTO Customer ('FirstName', 'LastName', 'Email', 'Subscribed', 'Registration', 'Password', 'Phone', 'Lat', 'Lon') VALUES (:first_name, :last_name, :email, :subscribed, :registration, :password, :phone, :lat, :lon);");
                    try
                    {
                        $stmt->bindParam(':first_name', $_POST['first_name']);
                        $stmt->bindParam(':last_name', $_POST['last_name']);
                        $stmt->bindParam(':email', $_POST['email']);
                        $stmt->bindValue(":subscribed", 0);
                        $stmt->bindValue(':registration', '');
                        $stmt->bindParam(':password', $_POST['password']);
                        $stmt->bindParam(':phone', $_POST['phone']);
                        $stmt->bindValue(':lat', "");
                        $stmt->bindValue(':lon', "");
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                        echo $e->getMessage();
                        $flag = 0;
                    }
                }
            }
            else if ($_POST['user_type'] == 'professional')
            {
                // Create Professional object
                $professional = new Professional($_POST['email'], $_POST['password'], 'professional');

                // If ID is not -1, customer already exists
                if ($professional->getID() != -1)
                    $flag = 0;

                // Prepare SQL statement
                if ($flag == 1)
                {
                    $stmt = $pdo->prepare("INSERT INTO Professional ('FirstName', 'LastName', 'Email', 'Password', 'Phone', 'Lat', 'Lon', 'BusinessName') VALUES (:first_name, :last_name, :email, :password, :phone, :lat, :lon, :business_name);");
                    try
                    {
                        $stmt->bindParam(':first_name', $_POST['first_name']);
                        $stmt->bindParam(':last_name', $_POST['last_name']);
                        $stmt->bindParam(':email', $_POST['email']);
                        $stmt->bindParam(':password', $_POST['password']);
                        $stmt->bindParam(':phone', $_POST['phone']);
                        $stmt->bindValue(':lat', "");
                        $stmt->bindValue(':lon', "");
                        $stmt->bindValue(':business_name', '');
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                        echo $e->getMessage();
                        $flag = 0;
                    }
                }
            }
        }
        ?>
        <img id="logo" src="images/logo.png"/>
        <div class="container-fluid">
            <div class="col-12" id="outer">
                <h1 id="message"></h1>
            </div>
        </div>
    </body>
    <script>
        // Get status flag
        var status = Number.parseInt(<?php echo $flag; ?>);
        var homeLink = "<br/><br/>If you are not automatically redirected, click <a href='index.php'>here</a> to return to the home page.";

        // If status 0, there was an error. Show error message and redirect after 3 seconds.
        if (status == 0)
        {
            document.getElementById('message').innerHTML =
                "There was an error. Please wait while we return you to the home page." + homeLink;

            setTimeout(function() {
                window.location.href = "index.php";
            }, 3000);
        }
        else if (status == 1)
        {
            // Display success message, then redirect to the home page after 3 seconds
            document.getElementById('message').innerHTML =
                "Success! Redirecting you to the home page. Please wait..." + homeLink;

            setTimeout(function() {
                window.location.href = "index.php";
            }, 3000);
        }
    </script>
</html>