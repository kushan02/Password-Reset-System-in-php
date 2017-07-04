<?php

if (isset($_POST['email'])) {

    require_once 'db.php'; // Include database config
    require_once 'config.php'; // Include our config file

    $email = trim($_POST['email']); // Fetch the email entered by user
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $result = mysqli_query($con, sqlf($email));

    if (mysqli_num_rows($result) > 0) { // Check if the email exists in the database

        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row["name"] . ""; // Get the name of the user who requested reset
        }

        $token = bin2hex(openssl_random_pseudo_bytes(20)); // Genrate a Random unique token key

        date_default_timezone_set('America/New_York'); // Set timezone to Newyork for this instance

        $expiry = date('Y-m-d H:i:s', strtotime('+24 hours')); // Set the expiry to 24 hours from time of request

        if (mysqli_query($con, sqlonef($email, $token, $expiry))) {  // Add the entry of password reset to the database

            $subject = "Reset your Password"; // Subject for sending email to user

            $reset_link = constant("RESET_PAGE_LOCATION") . "?token=" . $token . ""; // The password reset link which user will recieve in email

            $url = constant("EMAIL_PAGE_LOCATION");
            $data = array('name' => $name, 'email' => $email, 'reset_link' => $reset_link);

            // Send a post request to our email template file to get the generated message body for password reset email

            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context); // Fetch the generated code to variable
            if (!($result === FALSE)) {

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; // Specify the content of mail is of HTML type

                $headers .= "From: " . constant("EMAIL_ID_FROM") . "\r\n";;

                if (mail($email, $subject, $result, $headers)) { // Send mail using php mail function

                    echo '<div class="alert alert-success alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  A link to reset your password has been sent to ' . $email . '</div>';

                } else {
                    echo '<div class="alert alert-danger alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> Email not sent. Please try again.
</div>';
                }
            }
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> Email not registered. Please try again.
</div>';
    }

    mysqli_close($con); // Close the connection to database

}
?>
