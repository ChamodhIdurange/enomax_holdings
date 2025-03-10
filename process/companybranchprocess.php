<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$company=addslashes($_POST['company']);
$branch=addslashes($_POST['branch']);
$code=addslashes($_POST['code']);
$addressone=addslashes($_POST['addressone']);
$addresstwo=addslashes($_POST['addresstwo']);
$mobile=addslashes($_POST['mobile']);
$phone=addslashes($_POST['phone']);
$email=addslashes($_POST['email']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_company_branch`(`code`, `branch`, `address1`, `address2`, `mobile`, `phone`, `email`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_company_idtbl_company`) VALUES ('$code','$branch','$addressone','$addresstwo','$mobile','$phone','$email','1','$updatedatetime','$userID','$company')";
    if($conn->query($insert)==true){        
        header("Location:../companybranch.php?action=4");
    }
    else{header("Location:../companybranch.php?action=5");}
}
else{
    $update="UPDATE `tbl_company_branch` SET `code`='$code',`branch`='$branch',`address1`='$addressone',`address2`='$addresstwo',`mobile`='$mobile',`phone`='$phone',`email`='$email',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_company_idtbl_company`='$company' WHERE `idtbl_company_branch`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../companybranch.php?action=6");
    }
    else{header("Location:../companybranch.php?action=5");}
}
?>