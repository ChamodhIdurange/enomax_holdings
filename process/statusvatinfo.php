<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];
$type=$_GET['type'];

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

$sql="UPDATE `tbl_vat_info` SET `status`='$value',`updatedatetime`='$updatedatetime' WHERE `idtbl_vat_info`='$record'";
if($conn->query($sql)==true){header("Location:../vatinfo.php?action=$type");}
else{header("Location:../vatinfo.php?action=5");}
?>