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
    <!-- Page content -->
    <img id="logo" src="images/logo.png"/>
    <div class="container-fluid">
        <div class="col-12" id="outer">
            <div id="login">
                <h2 id="login_header">Sign Up</h2>
                <form method="post" name="register_form" action="/register_redirect.php">
                    <table id="form_table">
                        <tr>
                            <td>First Name</td>
                            <td>Last Name</td>
                        </tr>
                        <tr>
                            <td><input type="text" name="first_name" class="standard_input"/></td>
                            <td><input type="text" name="last_name" class="standard_input"/></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>Phone Number</td>
                        </tr>
                        <tr>
                            <td><input type="text" name="email" class="standard_input"/></td>
                            <td><input type="text" name="phone" class="standard_input"/> </td>
                        <tr>
                            <td colspan="2">Address</td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="text" name="address" class="colspan_2"/> </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td> Repeat Password</td>
                        </tr>
                        <tr>
                            <td><input type="password" id="password" name="password" class="standard_input"/></td>
                            <td><input type="password" id="password_repeat" name="password_repeat" class="standard_input"/></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                I am a:
                            </td>
                        </tr>
                        <tr>
                            <td id="radio_buttons" colspan="2">
                                <input type="radio" id="customer" name="user_type" value="customer" checked/>
                                <label for="customer">Customer</label>
                                <br/>
                                <input type="radio" id="professional" name="user_type" value="professional" />
                                <label for="professional">Professional</label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="button" value="Sign Up" id="register_button" class="standard_input" onclick="submitForm()"/>
                                <br/>
                                <span id="error_message"></span>
                            </td>
                        </tr>
                    </table>
                </form>

            </div>
        </div>
    </div>
    </body>
    <script>
        function submitForm()
        {
            // Get form data
            var formData = new FormData(document.querySelector('form'));

            // Error message span
            var error_msg = document.getElementById('error_message');

            // If passwords do not match
            if (formData.get('password') != formData.get('password_repeat'))
            {
                error_msg.innerHTML = "Passwords do not match!";
                error_msg.style = "color:red;";
                return;
            }

            // Make sure all fields are filled
            for (var entry of formData.entries())
            {
                if (entry[1] == '')
                {
                    error_msg.innerHTML = "Not all fields complete!";
                    error_msg.style = "color:red;";
                    return;
                }
            }

            // Clear error message
            error_msg.innerHTML = "";

            // Submit the form TODO
            document.register_form.submit();
        }
    </script>
    <style>
        #login
        {
            position: absolute;
            width: 50%;
            height: 85%;
            left: 25%;
            top: 10%;
            background: #F5F5F5;
            border-radius: 100px;
        }

        #login_header
        {
            position: absolute;
            width: 70%;
            left: 15%;
            top: 5%;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 100;
            font-size: 3em;
            line-height: 24px;
            color: #6C510B;
            text-align: center;
        }

        #form_table
        {
            position: absolute;
            top: 15%;
            left: 10%;
            width: 80%;
        }

        #form_table td
        {
            text-align: center;
        }

        .standard_input
        {
            box-sizing: border-box;
            width: 80%;
            height: 11.1%;
            background: #FFFFFF;
            border-radius: 50px;
            padding: 2%;
        }

        .colspan_2
        {
            box-sizing: border-box;
            background: #FFFFFF;
            border-radius: 50px;
            width: 90%;
            padding: 1%;
        }

        #register_button
        {
            width: 40%;
            box-sizing: border-box;
            background: #FFFFFF;
            border-radius: 40px;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 32px;
            line-height: 24px;
            color: #6C510B;
        }

        #register_button:hover
        {
            background: #DDDDDD;
        }

        td
        {
            width: 23.6%;
            height: 2.8%;
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 275;
            font-size: 1.5em;
            color: #6C510B;
        }
    </style>
</html>