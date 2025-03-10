<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];

$sql="UPDATE `tbl_invoice` SET `paymentcomplete`='1',`updatedatetime`='$updatedatetime' WHERE `idtbl_invoice`='$record'";
if($conn->query($sql)==true){header("Location:../paymentreceipt.php?action=2");}
else{header("Location:../paymentreceipt.php?action=5");}

?>