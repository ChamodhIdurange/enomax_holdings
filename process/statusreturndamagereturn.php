<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php'); 

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

$today=date('Y-m-d');

if($type==1){
    $sql="UPDATE `tbl_damage_return` SET `comsendstatus`='1',`comsenddate`='$today',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_damage_return`='$record'";
    $value=1;
}
else if($type==2){
    $sql="UPDATE `tbl_damage_return` SET `backstockstatus`='1',`backstockdate`='$today',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_damage_return`='$record'";
    $value=2;
}
else if($type==3){
    $sql="UPDATE `tbl_damage_return` SET `returncusstatus`='1',`returncusdate`='$today',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_damage_return`='$record'";
    $value=3;
}

if($conn->query($sql)==true){header("Location:../damagereturn.php");}
else{header("Location:../damagereturn.php?action=5");}
?>