<?php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASSWORD = "";
$DB_NAME = "erav_enomax_v3";

// $DB_NAME = "erav_connectswitches";
// $DB_USER = "root";
// $DB_PASSWORD = "";
// $DB_HOST = "localhost";


//Connecting to Database
$con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if (!$con) {
    echo "Connection Error..".mysqli_connect_error();
} else {
    // echo"<h3>Database Connection success...</h3>";
    date_default_timezone_set("Asia/Kolkata");
    $now = new DateTime();
    $mins = $now->getOffset() / 60;

    $sgn = ($mins < 0 ? -1 : 1);
    $mins = abs($mins);
    $hrs = floor($mins / 60);
    $mins -= $hrs * 60;

    $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
    $con->query("SET time_zone='$offset';");
}

?>
