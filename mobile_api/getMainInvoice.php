<?php
require_once('dbConnect.php');

$cusId=$_POST["cusId"];

$sql="SELECT * FROM `tbl_invoice_payment` WHERE `status`=1 AND `idtbl_invoice_payment` IN (SELECT `tbl_invoice_payment_idtbl_invoice_payment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$cusId')) GROUP BY `idtbl_invoice_payment`";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_invoice_payment'], "date" => $row['date'],"payment" => $row['payment'],"balance" => $row['balance'],"status" => $row['status']));
}

print(json_encode($result));
mysqli_close($con);
?>