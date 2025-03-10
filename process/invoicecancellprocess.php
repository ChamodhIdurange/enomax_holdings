<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php'); 

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];
$currentDate = date('mdY');
$updatedatetime = date('Y-m-d h:i:s');

if($type==1){$value=1;}
else if($type==2){$value=2;}
else if($type==3){$value=3;}
$batchNo = "RMV" . $currentDate . $record;

$sql="UPDATE `tbl_invoice` SET `status`='$value',`tbl_user_idtbl_user`='$userID', `cancelreason` = 'Cancelled directly from Invoice Page' WHERE `idtbl_invoice`='$record'";
if($conn->query($sql)==true){

    $sqlgetqty = "SELECT `qty`, `tbl_product_idtbl_product` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice` = '$record'";
    $resultsqlgetqty = $conn->query($sqlgetqty);

    if ($resultsqlgetqty->num_rows > 0) {
        while ($row = $resultsqlgetqty->fetch_assoc()) {
            $qty = $row['qty'];
            $productId = $row['tbl_product_idtbl_product'];

            $insertstock="INSERT INTO `tbl_stock` (`batchqty`, `qty`, `update`, `status`, `batchno`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `insertdatetime`) VALUES ('$qty', '$qty', '$updatedatetime', '1', '$batchNo', '$updatedatetime', '$userID', '$productId', '$updatedatetime')";
            $conn->query($insertstock);
        }
    }
    header("Location:../invoiceview.php?action=$type");
}
else{
    header("Location:../invoiceview.php?action=5");
}
?>