<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$code=$_POST['code'];
$name=$_POST['name'];
$telephone=$_POST['phone'];
$address=$_POST['address'];
$mobile=$_POST['mobile'] ?? '';
$email = $_POST['email'] ?? '';
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_company`
    ( `code`, `name`, `address1`, `address2`, `mobile`, `phone`, `email`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES 
    ('$code','$name','$address','','$mobile','$telephone','$email',1,'$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
       //
         header("Location:../company-info.php?action=4");
    }
    else{
         header("Location:../company-info.php?action=5");
    }
}
else{

    $update="UPDATE `tbl_company` SET `code`='$code',`name`='$name',`address1`='$address',`address2`='',`mobile`='$mobile',`phone`='$telephone',`email`='$email',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user` = $userID WHERE `idtbl_company` = $recordID";

    if($conn->query($update)==true){     
        header("Location:../company-info.php?action=6");
    }
    else{
        header("Location:../company-info.php?action=5");
    }
}
?>