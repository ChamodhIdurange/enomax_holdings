<?php
require_once('dbConnect.php');

$lorryId = $_POST['lorryId'];

$sql = "UPDATE `tbl_user_logindata` SET `logoutstatus`='1' WHERE `lorryid`='$lorryId' AND `logindate`=DATE(NOW())";
$res = mysqli_query($con, $sql);
if ($res) {
    $response = array("code" => '200', "message" => 'Update Complete');
    print_r(json_encode($response));
} else {
    $response = array("code" => '500', "message" => 'Update Not Complete');
    print_r(json_encode($response));
}

?>
