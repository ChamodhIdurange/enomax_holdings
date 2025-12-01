<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:../index.php");
}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$record = $_GET['record'];
$type = $_GET['type'];

if ($type == 1) {
    $value = 1;
} else if ($type == 2) {
    $value = 2;
} else if ($type == 3) {
    $value = 3;

    $getOldData = "SELECT `unitprice`, `saleprice` FROM `tbl_product` WHERE `idtbl_product`='$record'";
    $resultOldData = $conn->query($getOldData);

    if ($resultOldData->num_rows > 0) {
        $oldData = $resultOldData->fetch_assoc();
        $old_unitprice = $oldData['unitprice'];
        $old_saleprice = $oldData['saleprice'];

        $updatedatetime = date('Y-m-d H:i:s');

        $logQuery = "INSERT INTO `tbl_products_update_log`(`product_id`, `action_type`, `old_unit_price`, `old_sale_price`, `new_unit_price`, `new_sale_price`, `updated_by`, `updated_datetime`) 
                     VALUES ('$record', 'DELETE', '$old_unitprice', '$old_saleprice', NULL, NULL, '$userID', '$updatedatetime')";
        $conn->query($logQuery);
    }
}

$sql = "UPDATE `tbl_product` SET `status`='$value', `tbl_user_idtbl_user`='$userID' WHERE `idtbl_product`='$record'";

if ($conn->query($sql) == true) {
    header("Location:../product.php?action=$type");
} else {
    header("Location:../product.php?action=5");
}
