<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$code=$_POST['code'];
$branch=$_POST['branch'];
$phone=$_POST['telephone'];
$address=$_POST['address'];
$bank=$_POST['bank'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_bank_branch`(`code`, `branchname`, `phone`, `address`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_bank_idtbl_bank`) VALUES ('$code','$branch','$phone','$fax','$address','1','$updatedatetime','$userID','$bank')";
    if($conn->query($insert)===true){
        header("Location:../branch-info.php?action=4");
    }
    else{
        header("Location:../branch-info.php?action=5");
    }
}
else{
    $update="UPDATE `tbl_bank_branch` SET `code`='$code',`branchname`='$branch',`phone`='$phone',`address`='$address',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_bank_idtbl_bank`='$bank' WHERE `idtbl_bank_branch`='$recordID'";
    if($conn->query($update)==true){
        header("Location:../branch-info.php?action=6");
    }
    else{
        header("Location:../branch-info.php?action=5");
    }
}
?>