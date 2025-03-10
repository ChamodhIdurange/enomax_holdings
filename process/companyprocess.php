<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$company=addslashes($_POST['company']);
$code=addslashes($_POST['code']);
$addressone=addslashes($_POST['addressone']);
$addresstwo=addslashes($_POST['addresstwo']);
$mobile=addslashes($_POST['mobile']);
$phone=addslashes($_POST['phone']);
$email=addslashes($_POST['email']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_company`(`code`, `name`, `address1`, `address2`, `mobile`, `phone`, `email`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$code','$company','$addressone','$addresstwo','$mobile','$phone','$email','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../company.php?action=4");
    }
    else{header("Location:../company.php?action=5");}
}
else{
    $update="UPDATE `tbl_company` SET `code`='$code',`name`='$company',`address1`='$addressone',`address2`='$addresstwo',`mobile`='$mobile',`phone`='$phone',`email`='$email',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_company`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../company.php?action=6");
    }
    else{header("Location:../company.php?action=5");}
}
?>