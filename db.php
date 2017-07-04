<?php

define('DB_HOST', 'localhost'); // Replace with the name of your mysql server host
define('DB_USER', 'root'); // Replace with your phpmyadmin username
define('DB_PASS', 'mysql'); // Replace with your phpmyadmin password
define('DB_NAME', 'databasename'); // Replace with the name of database you created

// Create a connection with the mysql database
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Some error occurred during connection, Please try again! " . mysqli_error($con));  ;

?>