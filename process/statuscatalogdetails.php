<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_POST['id'];
$type=$_POST['value'];

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$sql="UPDATE `tbl_catalog_details` SET `status`='$value',`tbl_userid`='$userID' WHERE `idtbl_catalog_details`='$record'";
if($conn->query($sql)==true){}
else{header("Location:../productcatalog.php?action=5");}
?>