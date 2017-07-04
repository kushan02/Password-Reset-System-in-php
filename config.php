<?php
//---------------------------------------------
// CONFIG FILE FOR PASSWORD RESET SYSTEM //
//---------------------------------------------


define("PRODUCT_NAME", "[Product Name]"); // The name of your product (eg. Gmail)
define("COMPANY_NAME", "[Company Name, LLC]"); // The name of you company (eg. Google)
define("ADDR_LINE_1", "1234 Street Rd."); // Line 1 of your address
define("ADDR_LINE_2", "Suite 1234"); // Line 2 of your address

define("EMAIL_ID_FROM","webmaster@kushan02.xyz"); // The email-id of your domain which would be seen in from field


define("RESET_PAGE_LOCATION", "http://kushan:kushan@client.kushan02.xyz/hostinger/forgot-pass/reset.php"); // Link to file on your website where reset.php is stored

define("EMAIL_PAGE_LOCATION", "http://kushan:kushan@client.kushan02.xyz/hostinger/forgot-pass/email.php"); // Link to file on your website where email.php is stored


/* Here 'users' is the name of table where registered users are stored
email - table column where email address of registered users are stored
*/
function sqlf($email)
{
    $sql = "SELECT * FROM users WHERE email='$email'";
    return $sql;

}
/* Here 'forgot_pass' is the name of table where new generated tokens are stored
 email - email address of user who requested the password reset
   token - unique random generated token identifier for reset link
   expiry - 24 hours from time of request from which the link wont be a valid link
*/
function sqlonef($email,$token,$expiry)
{
    $sqlone =  "INSERT INTO forgot_pass (email,token,expiry)VALUE ('$email','$token','$expiry')";
    return $sqlone;
}


function pass_encrypt($password)
{
    /* Change the below encryption algorithm if you using different one to store passwords in your database (eg. sha1 etc)
    Remove below line if not using encryption (not recommended)
    */

    // Encrypt password using sha512 so that no one can infer the password directly from the database
    $password = hash('sha512', $password);

    return $password;
}

/* Here 'users' is the name of table where registered users are stored
email - table column where email address of registered users are stored
pass - table column where password encrypted using sha-512 are stored
*/
function sqlresetf($email,$password)
{
    $sqlreset =  "UPDATE users SET pass='$password' WHERE email='$email'";

    return $sqlreset;
}


/* Here 'forgot_pass' is the name of table where new generated tokens are stored
 email - email address of user who requested the password reset
   token - unique random generated token identifier for reset link
   expiry - 24 hours from time of request from which the link wont be a valid link
*/
function sqldelf($email,$token)
{
    $sqldel = "DELETE FROM forgot_password WHERE email='$email' AND token='$token'";

    return $sqldel;
}





?>