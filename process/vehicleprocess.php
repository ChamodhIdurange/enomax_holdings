<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$vehicleno=addslashes($_POST['vehicleno']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_vehicle`(`vehicleno`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$vehicleno','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../vehicle.php?action=4");
    }
    else{header("Location:../vehicle.php?action=5");}
}
else{
    $update="UPDATE `tbl_vehicle` SET `vehicleno`='$vehicleno',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_vehicle`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../vehicle.php?action=6");
    }
    else{header("Location:../vehicle.php?action=5");}
}
?>