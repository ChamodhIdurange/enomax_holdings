<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$accountType=$_POST['accountType'];
$bank=$_POST['bank'];
$accountName=$_POST['accountName'];
$accountNo=$_POST['accountNo'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_account`
    ( `account`, `accountno`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_account_type_idtbl_account_type`, `tbl_bank_idtbl_bank`) VALUES 
    ('$accountName', '$accountNo', '1','$updatedatetime','$userID', '$accountType', '$bank')";
    if($conn->query($insert)==true){        
         header("Location:../account.php?action=4");
    }
    else{
         header("Location:../account.php?action=5");
    }
}
else{
    $update="UPDATE `tbl_account` SET `account`='$accountName', `accountno`='$accountNo', `updatedatetime`='$updatedatetime',`tbl_user_idtbl_user` = '$userID', `tbl_account_type_idtbl_account_type`='$accountType', `tbl_bank_idtbl_bank`='$bank' WHERE `idtbl_account` = $recordID";

    if($conn->query($update)==true){     
        header("Location:../account.php?action=6");
    }
    else{
        header("Location:../account.php?action=5");
    }
}
?>