<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$paymenttype=addslashes($_POST['paymenttype']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_paymenttype`(`paymenttype`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$paymenttype','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../paymenttype.php?action=4");
    }
    else{header("Location:../paymenttype.php?action=5");}
}
else{
    $update="UPDATE `tbl_paymenttype` SET `paymenttype`='$paymenttype',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_paymenttype`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../paymenttype.php?action=6");
    }
    else{header("Location:../paymenttype.php?action=5");}
}
?>