<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}

$updatedatetime=date('Y-m-d h:i:s');

$sql="UPDATE `tbl_invoice_payment` SET `status`='$value',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice_payment`='$record'";
if($conn->query($sql)==true){
    $updatepaymentdetail="UPDATE `tbl_invoice_payment_detail` SET `status`='$value',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$record'";
    $conn->query($updatepaymentdetail);
    
    $sqlcheck="SELECT `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$record'";
    $resultcheck =$conn-> query($sqlcheck); 
    while($rowcheck = $resultcheck-> fetch_assoc()){
        $invoiceID=$rowcheck['tbl_invoice_idtbl_invoice'];

        $updateinvoice="UPDATE `tbl_invoice` SET `paycomplete`='0',`updatedatetime`='',`tbl_user_idtbl_user`='' WHERE `idtbl_invoice`='$invoiceID'";
        $conn->query($updateinvoice);
    }

    $deletehastable="DELETE FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$record'";
    $conn->query($deletehastable);

    header("Location:../paymentreceipt.php?action=$type");
}
else{header("Location:../paymentreceipt.php?action=5");}
?>