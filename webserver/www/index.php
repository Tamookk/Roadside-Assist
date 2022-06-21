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
    </head>
    <body>
        <?php
            // Log out a user if a GET parameter and a cookie are set
            if (isset($_GET['logout']))
            {
                // Clear customer cookie
                if (isset($_COOKIE['customer']) && $_GET['logout'] == '1')
                {
                    setcookie('customer', '', time() + 300, "/");
                }
                // Clear professional cookie
                else if (isset($_COOKIE['professional']) && $_GET['logout'] == '1')
                {
                    setcookie('professional', '', time() + 300, "/");
                }
            }

            // Get user type from GET - default to customer if not set
            $user_type = $_GET['user_type'] ?? 0;
        ?>
        <img id="logo" src="images/logo.png"/>
        <div class="container-fluid" id="outer">
            <div class="row">
                <!-- Login -->
                <div id="login" class="col-12">
                    <form action="register.php" method="post" id="register_form"></form>
                    <form method="post" name="login_form" action="/home.php">
                        <?php
                            // Professional header and hidden input
                            if ($user_type == 2)
                            {
                                echo '<h2 id="login_header">Professional Login</h2>';
                                echo '<input type="hidden" name="user_type" value="professional"/>';
                            }
                            // Customer/default header and hidden input
                            else
                            {
                                echo '<h2 id="login_header">Customer Login</h2>';
                                echo '<input type="hidden" name="user_type" value="customer"/>';
                            }
                        ?>
                        <span id="email_text">Email:</span>
                        <br/>
                        <input type="text" name="username" id="email_field"/>
                        <br/>
                        <span id="password_text">Password:</span>
                        <br/>
                        <input type="password" name="password" id="password_field"/>
                        <br/>
                        <div id="register_button_div">
                            <input type="submit" id="register_button" value="Register" form="register_form"/>
                        </div>
                        <div id="submit_button_div">
                            <input type="submit" id="submit_button" value="Login"/>
                        </div>
                        <br/>
                        <?php
                            // Professional version
                            if ($user_type == 2)
                            {
                                echo '<span id="hint">For the customer login portal, <a href="index.php" id="click_span">click here</a></span>';
                            }
                            // Customer/default version
                            else
                            {
                                echo '<span id="hint">For the professional login portal, <a href="index.php?user_type=2" id="click_span">click here</a></span>';
                            }
                        ?>

                    </form>
                </div>
            </div>
            <div class="row">
                <!-- Login hint (not for production!) -->
                <div class="col-12" id="login_hint">
                    <h2>Hint:</h2>
                    <p>Log in as a customer with email "e.barrett@email.com" and password "123456" to test customer functionalities.</p>
                    <p>Log in as a professional with email "f.west@email.com" and password "william" to test professional functionalities.</p>
                </div>
            </div>
        </div>
    </body>
    <style>
        #outer
        {
            background-image: url("images/seacliff_bridge.png");
        }

        #login
        {
            position: absolute;
            width: 36%;
            height: 60%;
            left: 31.8%;
            top: 25%;
            background: #F5F5F5;
            border-radius: 100px;
        }

        #email_field
        {
            box-sizing: border-box;
            position: absolute;
            width: 80%;
            height: 11.1%;
            left: 10%;
            top: 28.3%;
            background: #FFFFFF;
            border-radius: 50px;
            padding: 1%;
        }

        #password_field
        {
            box-sizing: border-box;
            position: absolute;
            width: 80%;
            height: 11.1%;
            left: 10%;
            top: 53.4%;
            background: #FFFFFF;
            border-radius: 50px;
            padding: 1%;
        }

        #submit_button_div
        {
            position: absolute;
            width: 36.2%;
            height: 11%;
            left: 53.8%;
            top: 70%;
        }

        #submit_button
        {
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            background: #FFE67A;
            border-radius: 40px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 32px;
            line-height: 24px;
            color: #6C510B;
        }

        #hint
        {
            position: absolute;
            width: 80%;
            height: 4.4%;
            left: 10%;
            top: 83%;

            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1em;
            color: #6C510B;
        }

        #click_span
        {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1em;
            text-decoration-line: underline;
            color: #281AC8;
        }

        #register_button_div
        {
            position: absolute;
            width: 36.2%;
            height: 11%;
            left: 10%;
            top: 70%;
        }

        #register_button
        {
            box-sizing: border-box;
            position: absolute;
            width: 100%;
            height: 100%;
            background: #FFFFFF;
            border-radius: 40px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 32px;
            line-height: 24px;
            color: #6C510B;
        }

        #password_text
        {
            position: absolute;
            width: 23.6%;
            height: 2.8%;
            left: 10%;
            top: 45%;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.5em;
            color: #6C510B;
        }

        #email_text
        {
            position: absolute;
            width: 23.6%;
            height: 2.8%;
            left: 10%;
            top: 19%;
            font-family: 'Poppins';
            font-weight: 275;
            font-size: 1.5em;
            color: #6C510B;
        }

        #login_header
        {
            position: absolute;
            width: 70%;
            height: 4.4%;
            left: 15%;
            top: 8%;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 100;
            font-size: 3em;
            line-height: 24px;
            color: #6C510B;
            text-align: center;
        }

        #login_hint
        {
            background-color: white;
            position: absolute;
            bottom: 0%;
            left: 20%;
            color: black;
            width: 50%;
        }
    </style>
</html>


