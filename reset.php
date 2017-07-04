<?php

if (isset($_GET["token"])) {

    $token = $_GET["token"]; // Get the token key using GET request from the url (?token=xyz -> xyz)

    require_once 'db.php';
    require_once 'config.php';

    date_default_timezone_set('America/New_York'); // Set default timezone for server for this instance to New York

    $date = date('Y-m-d H:i:s'); // Get current timestamp from server to compare expiry

    // Ensure that token specified exists in our database and has not expired or already used to reset password once

    $sqlreset = "SELECT * FROM forgot_pass WHERE token = '$token' AND expiry>'$date' ";

    $result = mysqli_query($con, $sqlreset);

    if (mysqli_num_rows($result) > 0) { // Display the Reset form only if the token exists and has validity

        while ($row = mysqli_fetch_assoc($result)) {
            $email = $row["email"] . "";
        }

        ?>


        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Reset Password - Hostinger Tutorials</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
                  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
                  crossorigin="anonymous">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
                  integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
                  crossorigin="anonymous">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
                    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
                    crossorigin="anonymous"></script>

            <script>

                function resetpass(e) {


                    e.preventDefault();
                    $("#submit").prop("disabled", true);
                    $("#result").empty();
                    var pass = document.getElementById('passwordid').value;

                    var dataString = {'password': pass, 'email': '<? echo $email; ?>'};
                    $.ajax({
                        url: "reset_password.php",
                        type: "post",
                        data: dataString,
                        success: function (response) {
                            $("#result").append(response);
                        }
                    });
                    //   $("#submit").prop("disabled", false);
                }

            </script>

            <style>

                body {
                    font-family: Circular, "Helvetica Neue", Helvetica, Arial, sans-serif;
                    font-size: 14px;
                    line-height: 1.43;
                    color: #484848;
                    -webkit-font-smoothing: antialiased;
                }

                #forgot_div {
                    margin: 10% 34%
                }

                @media only screen and (min-width: 769px) {
                    #forgot_div {
                        max-width: 32%;
                    }

                }

                @media only screen and (max-width: 768px) {
                    #forgot_div {
                        max-width: 90%;
                        min-width: 90%;
                        margin: 0 5%;
                    }
                }

                .mybtn {
                    border-color: #ff5a5f !important;
                    background-color: #ff5a5f !important;
                    color: #fff !important;
                    margin-bottom: 0;
                    border-radius: 4px;
                    border: 1px solid;
                    text-align: center;
                    vertical-align: middle;
                    font-weight: bold;
                    line-height: 1.43;
                    user-select: none;
                    padding: 9px 27px;
                    font-size: 16px;
                }
            </style>

        </head>

        <body>

        <div class="container">
            <div id="forgot_div">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Reset Password</strong></div>
                    <div class="panel-body">
                        <div class="panel-body">
                            <form id="resetform" autocomplete="off" method="post" onsubmit="resetpass(event)">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" id="passwordid"
                                           placeholder="New Password" required pattern="^\S{6,}$"
                                           onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Must have at least 6 characters' : ''); if(this.checkValidity()) form.password_two.pattern = this.value;">
                                </div>
                                <div class="form-group">
                                    <input id="password_two" required class="form-control" name="password_two"
                                           type="password" pattern="^\S{6,}$"
                                           onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Please enter the same Password as above' : '');"
                                           placeholder="Confirm Password" required>
                                </div>
                                <input type="hidden" name="email" id="email" value="<? echo $email; ?>">

                                <div class="form-group">
                                    <input class="btn btn-block btn-large mybtn" id="submit" name="reset-btn"
                                           value="Save &amp; Continue"
                                           type="submit">
                                </div>

                            </form>

                            <div id="result"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        </body>
        </html>

        <?
    } else { // Show the error if token is not valid or expired
        echo "Requested Link Not found, Make sure you have followed a valid link and you have not already reset it or link has not already expired!";

        /* Instead of the above message you can also redirect the user to the error page or your choice using the below code */

        //     header("Location: http://www.mypage.com/page_not_found.php" );


    }
} else { // Show the error if token is not valid or expired
    echo "Requested Link Not found, Make sure you have followed a valid link and your link has not already expired!";

    /* Instead of the above message you can also redirect the user to the error page or your choice using the below code */

    //     header("Location: http://www.mypage.com/page_not_found.php" );
}
?>