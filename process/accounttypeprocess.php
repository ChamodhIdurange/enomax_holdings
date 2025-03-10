<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$accountType=$_POST['accountType'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_account_type`
    ( `accounttype`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES 
    ('$accountType', '1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
         header("Location:../accounttype.php?action=4");
    }
    else{
         header("Location:../accounttype.php?action=5");
    }
}
else{
    $update="UPDATE `tbl_account_type` SET `accounttype`='$accountType', `updatedatetime`='$updatedatetime',`tbl_user_idtbl_user` = $userID WHERE `idtbl_account_type` = $recordID";

    if($conn->query($update)==true){     
        header("Location:../accounttype.php?action=6");
    }
    else{
        header("Location:../accounttype.php?action=5");
    }
}
?>