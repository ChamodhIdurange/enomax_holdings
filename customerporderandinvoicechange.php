<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("");
}
require_once('connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');


$sql = "SELECT * FROM `tbl_customer_order` WHERE `idtbl_customer_order` >= 1";
$result = $conn->query($sql);
$count=0;
if ($result->num_rows > 0) {
    // Loop through each row
    while ($row = $result->fetch_assoc()) {
        $count++;
        $orderId = $row["idtbl_customer_order"];
        $date = $row["date"];
        $customerId = $row["tbl_customer_idtbl_customer"];

        $updateInvoice = "UPDATE `tbl_invoice` SET `tbl_customer_idtbl_customer`='$customerId', `date`='$date' WHERE `tbl_customer_order_idtbl_customer_order`='$orderId'";
        $conn->query($updateInvoice);

        echo $count. ' - ';
    }
}




