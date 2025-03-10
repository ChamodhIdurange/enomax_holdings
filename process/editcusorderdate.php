<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$orderdate=$_POST['orderdate'];
$editorderid=$_POST['editorderid'];


$updatedatetime=date('Y-m-d h:i:s');


$updateorder="UPDATE `tbl_porder` SET `orderdate` = '$orderdate' WHERE `tbl_porder`.`idtbl_porder` = '$editorderid';";
if($conn->query($updateorder)==true){
    header("Location:../customerporder.php?action=6");
}
else{
    header("Location:../customerporder.php?action=5");
}

