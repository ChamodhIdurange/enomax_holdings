<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];
$type=$_GET['type'];


// print_r($record)
$sql="UPDATE `tbl_location_reorder` SET `status`='3' WHERE `idtbl_location_reorder`='$record'";
if($conn->query($sql)==true){header("Location:../productreorder.php?action=$type");}
else{header("Location:../productreorder.php?action=5");}
 ?>