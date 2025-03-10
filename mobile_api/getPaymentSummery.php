<?php
require_once('dbConnect.php');

$paymentinoiceID=$_POST["paymentinoiceID"];

$sql="SELECT `tbl_invoice_idtbl_invoice`,`payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['tbl_invoice_idtbl_invoice'], "payamount" => $row['payamount']));
}

print(json_encode($result));
mysqli_close($con);
?>