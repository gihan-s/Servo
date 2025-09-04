<?php
// when running the website on localhost, you'd have to connect to the remote database server
// set to true for locahost testing, false for deployment on web server
// once done testing on localhost, set it back to false
$isTestingLocally = true;

if ($isTestingLocally) {
    // need php mysqli extension enabled to locally testing
    $DB_SERVER = "13.60.4.254";
    $DB_USERNAME = "servodbuser";
    $DB_PASSWORD = "Pass@Servo2025";
    $DB_NAME = "servo";
} else {
    $DB_SERVER = "localhost";
    $DB_USERNAME = "webuser";
    $DB_PASSWORD = "Pass@Servo2025";
    $DB_NAME = "servo";
}

$conn = mysqli_connect($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>