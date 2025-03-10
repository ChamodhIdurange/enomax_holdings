<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$expencestype=addslashes($_POST['expencestype']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_expences_type`(`expencestype`, `status`, `updatedateime`, `tbl_user_idtbl_user`) VALUES ('$expencestype','1','$updatedatetime','$userID')";
    if($conn->query($insert)==true){        
        header("Location:../expensestype.php?action=4");
    }
    else{header("Location:../expensestype.php?action=5");}
}
else{
    $update="UPDATE `tbl_expences_type` SET `expencestype`='$expencestype',`updatedateime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_expences_type`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../expensestype.php?action=6");
    }
    else{header("Location:../expensestype.php?action=5");}
}
?>