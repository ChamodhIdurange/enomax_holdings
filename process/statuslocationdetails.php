<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];

$customerID=$_GET['customerID'];
$type=$_GET['type'];



$sql="UPDATE `tbl_customer_location` SET `status`='3',`updatedatetime`='$updatedatetime' WHERE `idtbl_customer_location`='$record'";
if($conn->query($sql)==true){
   
        header("Location:../customerprofile.php?action=3&record=$customerID");
   
}
else{
 
        header("Location:../customerprofile.php?action=6&record=$customerID");
}
?>