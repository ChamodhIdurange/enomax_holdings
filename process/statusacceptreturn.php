<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:../index.php");
}
require_once('../connection/db.php');

$updatedatetime = date('Y-m-d h:i:s');
$currentDate = date('mdY');
$userID = $_SESSION['userid'];
$record = $_GET['record'];
// $type=$_GET['type'];
$sqlReturn = "SELECT `returntype`, `total`, `tbl_customer_idtbl_customer` FROM `tbl_return` WHERE `idtbl_return` = '$record'";
$resultReturn = $conn->query($sqlReturn);
$rowReturn = $resultReturn->fetch_assoc();
$type =  $rowReturn['returntype'];
$returntotal =  $rowReturn['total'];
$customerId =  $rowReturn['tbl_customer_idtbl_customer'];

$sqlgetqty = "SELECT `qty`, `tbl_product_idtbl_product` FROM `tbl_return_details` WHERE `tbl_return_idtbl_return` = '$record'";
$resultsqlgetqty = $conn->query($sqlgetqty);

$sql = "UPDATE `tbl_return` SET `acceptance_status`='1',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_return`='$record'";
$batchNo = "RTH" . $currentDate . $record;

if ($conn->query($sql) == true) {
    if ($type == 1) {

        $sqlcredit = "INSERT INTO `tbl_creditenote`(`returnamount`, `payAmount`, `balAmount`, `baltotalamount`, `settle`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$returntotal', 0, '$returntotal', 0, 0, 1, '$updatedatetime', '$userID', '$customerId')";
        $conn->query($sqlcredit);
        $noteId = mysqli_insert_id($conn);


        $sqlcreditdetail = "INSERT INTO `tbl_creditenote_detail`(`returntotal`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_return_idtbl_return`, `tbl_creditenote_idtbl_creditenote`) VALUES ('$returntotal', 1, '$updatedatetime', '$userID', '$record', '$noteId')";
        $conn->query($sqlcreditdetail);

        if ($resultsqlgetqty->num_rows > 0) {
            while ($row = $resultsqlgetqty->fetch_assoc()) {
                $qty = $row['qty'];
                $tbl_product_idtbl_product = $row['tbl_product_idtbl_product'];

                $insertstock="INSERT INTO `tbl_stock` (`batchqty`, `qty`, `update`, `status`, `batchno`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `insertdatetime`) VALUES ('$qty', '$qty', '$updatedatetime', '1', '$batchNo', '$updatedatetime', '$userID', '$tbl_product_idtbl_product', '$updatedatetime')";
                $conn->query($insertstock);
            }
        }
        header("Location:../customerreturn.php?action=6");
    } else if ($type == 2) {
        $sqlcredit = "INSERT INTO `tbl_creditenote`(`returnamount`, `payAmount`, `balAmount`, `baltotalamount`, `settle`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$returntotal', 0, '$returntotal', 0, 0, 1, '$updatedatetime', '$userID', '$customerId')";
        $conn->query($sqlcredit);
        $noteId = mysqli_insert_id($conn);


        $sqlcreditdetail = "INSERT INTO `tbl_creditenote_detail`(`returntotal`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_return_idtbl_return`, `tbl_creditenote_idtbl_creditenote`) VALUES ('$returntotal', 1, '$updatedatetime', '$userID', '$record', '$noteId')";
        $conn->query($sqlcreditdetail);

        header("Location:../customerreturn.php?action=6");
    } else if ($type == 3) {
        $sqlcredit = "INSERT INTO `tbl_creditenote`(`returnamount`, `payAmount`, `balAmount`, `baltotalamount`, `settle`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$returntotal', 0, '$returntotal', 0, 0, 1, '$updatedatetime', '$userID', '$customerId')";
        $conn->query($sqlcredit);
        $noteId = mysqli_insert_id($conn);


        $sqlcreditdetail = "INSERT INTO `tbl_creditenote_detail`(`returntotal`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_return_idtbl_return`, `tbl_creditenote_idtbl_creditenote`) VALUES ('$returntotal', 1, '$updatedatetime', '$userID', '$record', '$noteId')";
        $conn->query($sqlcreditdetail);

        
        header("Location:../customerreturn.php?action=6");
    }
} else {
    if ($type == 1) {
        header("Location:../customerreturn.php?action=5");
    } else if ($type == 2) {
        header("Location:../customerreturn.php?action=5");
    } else if ($type == 3) {
        header("Location:../customerreturn.php?action=5");
    }
}
