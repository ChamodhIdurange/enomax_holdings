<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$bank=addslashes($_POST['bank']);
$bankbranch=addslashes($_POST['bankbranch']);
$paymenttype=addslashes($_POST['paymenttype']);
$accountno=addslashes($_POST['accountno']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_bank_account`(`accountno`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_bank_idtbl_bank`, `tbl_bank_branch_idtbl_bank_branch`, `tbl_paymenttype_idtbl_paymenttype`) VALUES ('$accountno','1','$updatedatetime','$userID','$bank','$bankbranch','$paymenttype')";
    if($conn->query($insert)==true){        
        header("Location:../bankaccount.php?action=4");
    }
    else{header("Location:../bankaccount.php?action=5");}
}
else{
    $update="UPDATE `tbl_bank_account` SET `accountno`='$accountno',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_bank_idtbl_bank`='$bank',`tbl_bank_branch_idtbl_bank_branch`='$bankbranch',`tbl_paymenttype_idtbl_paymenttype`='$paymenttype' WHERE `idtbl_bank_account`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../bankaccount.php?action=6");
    }
    else{header("Location:../bankaccount.php?action=5");}
}
?>