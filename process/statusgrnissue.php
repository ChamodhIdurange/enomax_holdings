<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php'); 

$userID=$_SESSION['userid'];
$poid=$_POST['poid'];
$updatedatetime=date('Y-m-d h:i:s');

$updateorder="UPDATE `tbl_porder` SET `grnissuestatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$poid'";
if($conn->query($updateorder)==true){
    header("Location:../invoiceview.php?action=6");
}
else{header("Location:../invoiceview.php?action=5");}

?>