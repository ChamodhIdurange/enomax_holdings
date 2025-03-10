
<?php
require_once('dbConnect.php');

$customerID = $_POST["customerID"];


$sql = "SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice_payment_has_tbl_invoice`.`payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 ";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array("invoice" => $row['idtbl_invoice'], "date" => $row['date'], "total" => $row['total'], "payamount" => $row['payamount']));
}

print(json_encode($result));
mysqli_close($con);
?>