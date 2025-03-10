<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$today = date('Y-m-d'); 

$poID=$_POST['poID'];
$total=$_POST['total'];
$nettotal=$_POST['nettotal'];
$discount=$_POST['discount'];
$podiscountPrecentage=$_POST['podiscountPrecentage'];
$podiscountAmount=$_POST['podiscountAmount'];
$remarkVal=$_POST['remarkVal'];


$updatedatetime=date('Y-m-d h:i:s');
$areaId=null;
$locationId=null;
$customerId=null;

$fullDiscount = $discount + $podiscountAmount;

// DELIVERED
$updatePoValues="UPDATE  `tbl_customer_order` SET `podiscount`='$podiscountAmount', `podiscountpercentage`='$podiscountPrecentage',   `discount`='$discount',`nettotal`='$nettotal', `total`='$total', `remark`='$remarkVal'  WHERE `idtbl_customer_order`='$poID'";

if($conn->query($updatePoValues)==true){
    $getinvoicedata = "SELECT * FROM `tbl_invoice` WHERE `tbl_customer_order_idtbl_customer_order` = '$poID'";
    $resultinvoice = $conn->query($getinvoicedata);

    if ($resultinvoice->num_rows > 0) {
        $rowinvoice = $resultinvoice->fetch_assoc();
        $invoiceId = $rowinvoice['idtbl_invoice'];
        $invoiceNo = $rowinvoice['invoiceno'];
    }

    $updateinvoicehead="UPDATE `tbl_invoice` SET `total` = '$total', `discount` = '$fullDiscount', `nettotal` = '$nettotal', `updatedatetime` = '$updatedatetime' WHERE `idtbl_invoice`='$invoiceId'";
    if($conn->query($updateinvoicehead)==true){
        
            
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-check-circle';
        $actionObj->title='';
        $actionObj->message='Record Updated Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='success';

        echo $actionJSON=json_encode($actionObj);
    }else{
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-exclamation-triangle';
        $actionObj->title='';
        $actionObj->message='Record Error';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='danger';

        echo $actionJSON=json_encode($actionObj);
    }

}

