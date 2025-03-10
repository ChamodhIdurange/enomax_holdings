<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$code=$_POST['code'];
$name=$_POST['name'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_bank`
    ( `bankname`, `code`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES 
    ('$name','$code','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
       //
         header("Location:../bank-info.php?action=4");
    }
    else{
         header("Location:../bank-info.php?action=5");
    }
}
else{

    $update="UPDATE `tbl_bank` SET `bankname`='$name',`code`='$code',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user` = $userID WHERE `idtbl_bank` = $recordID";

    if($conn->query($update)==true){     
        header("Location:../bank-info.php?action=6");
    }
    else{
        header("Location:../bank-info.php?action=5");
    }
}
?>