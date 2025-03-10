<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:../index.php");
}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$record = $_GET['record'];
$type = $_GET['type'];

if ($type == 1) {
    $value = 1;
} else if ($type == 2) {
    $value = 2;
} else if ($type == 3) {
    $value = 3;
} else if ($type == 8) {
    $value = 1;
}

if ($type == 8) {
    $sql = "UPDATE `tbl_porder` SET `completestatus`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
} else {
    $sql = "UPDATE `tbl_porder` SET `confirmstatus`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$record'";
}

if ($conn->query($sql) === true) {
    header("Location:../porder.php?action=$type");
} else {
    header("Location:../porder.php?action=5");
}
?>
