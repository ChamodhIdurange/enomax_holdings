<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

$today=date('Y-m-d');

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

if($type==4){
    $sql="UPDATE `tbl_customer` SET `emergencydate`='$today',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_customer`='$record'";
}
else{
    $sql="UPDATE `tbl_customer` SET `status`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_customer`='$record'";
}

if($conn->query($sql)==true){header("Location:../customer.php?action=$type");}
else{header("Location:../customer.php?action=5");}