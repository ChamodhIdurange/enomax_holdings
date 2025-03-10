<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$cancelreason=$_POST['cancelreason'];
$recordID=$_POST['recordID'];
$updatedatetime=date('Y-m-d h:i:s');


$updatePo="UPDATE  `tbl_customer_order` SET `status`='4',`cancelreason`='$cancelreason' WHERE `idtbl_customer_order`='$recordID'";
if($conn->query($updatePo)==true){
    $updateInvoice="UPDATE  `tbl_invoice` SET `status`='3',`cancelreason`='$cancelreason'  WHERE `tbl_customer_order_idtbl_customer_order`='$recordID'";
    $conn->query($updateInvoice);
  
    $updateholdstock="UPDATE  `tbl_customer_order_hold_stock` SET `status`='3' WHERE `tbl_customer_order_idtbl_customer_order`='$recordID'";
    $conn->query($updateholdstock);

    header("Location:../customerporder.php?action=3");
}else{
    header("Location:../customerporder.php?action=5");
}


