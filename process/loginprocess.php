<?php
// turn error reporting on, it makes life easier if you make typo in a variable name etc
error_reporting(E_ALL);

session_start();

define('TIMEZONE', 'Asia/Colombo');
date_default_timezone_set(TIMEZONE);

//Start Database
$IP = "localhost";
$user = "root";
$pass = "";
$db = "erav_enomax_v2";
$con = mysqli_connect($IP, $user, $pass, $db);

// Check connection
if (!$con) {
    echo "<div>";
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    echo "</div>";

    // *** what happens here, you let the script continue regardless of the error?
}

// Pretty much kicks out a user once they revisit this age and is logged in

// *** It is best to test isset($_SESSION["name"]), otherwise php will generate a warning if 'name' index is not set.
// you can also test for !empty($_SESSION["name"]), as empty detects if a value is not set, but it will also detect 0 as empty, so use with caution
// if( $_SESSION["name"] )
if( isset($_SESSION["user"]) && $_SESSION["user"] )
{
    //echo "You are already logged in, ".$_SESSION['user']."! <br> I'm Loggin you out M.R ..";
    unset( $_SESSION );
    session_destroy();

    // *** The empty quotes do nothing
    // exit("");
    exit;
}

$loggedIn = false;

// *** While or is nice solution, it doesn't take into account when the 'name' index is not set, which generates a php warning
// $userName = $_POST["name"] or "";
$userName = isset($_POST["username"]) ? $_POST["username"] : null;

// *** same change as above
// $userPass = $_POST["pass"] or "";
$userPass = isset($_POST["password"]) ? $_POST["password"] : null;
$userPass = md5($userPass);

// *** This test really comes down to, what if username or password is evaluated to false.
// have a good think about what it is you are actually testing
// php casts strings and numeric values to boolean, so something that you don't think is false could be cast as false, eg a string containing "0"
if ($userName && $userPass )
{
    // User Entered fields
    // *** This is dangerous, it is subject to sql injection, given you wrote this code in 2 days, i am sure you can find
    // plenty of info on sql injection and mysqli and improve it
    $query = "SELECT * FROM tbl_user WHERE username='$userName' and password='$userPass' AND status=1";// AND password = $userPass";

    $result = mysqli_query( $con, $query);

    // *** Error checking, what if !$result? eg query is broken

    $row = mysqli_fetch_array($result);
    
    $userid = $row['idtbl_user'];
    $name = $row['name'];
    $userName = $row['username'];
    $userType = $row['tbl_user_type_idtbl_user_type'];
    $userImage = $row['imagepath'];

    if(!$row){
//        echo "<div>";
//        echo "No existing user or wrong password.";
//        echo "</div>";
    }
    else {
        // *** My PERSONAL preference is to use {} every where, it just makes it easier if you add  
        // code into the condition later
        $loggedIn = true;

        $today=date('Y-m-d');
        $time=date('h:i:s');

        $sqlinsert = "INSERT INTO `tbl_user_logindata`(`lorryid`, `deviceid`, `logindate`, `logintime`, `logoutstatus`, `status`, `tbl_user_idtbl_user`) VALUES ('0','0','$today','$time','0','1','$userid')";
        mysqli_query($con, $sqlinsert);
    }
}

if ( !$loggedIn )
{
    header("location:../index.php?error=wrong");
}
else{
    header("location:../locations.php");
    $_SESSION['userid'] = $userid;
    $_SESSION['name'] = $name;
    $_SESSION['username'] = $userName;
    $_SESSION['type'] = $userType;
    $_SESSION['image'] = $userImage;
}
?>