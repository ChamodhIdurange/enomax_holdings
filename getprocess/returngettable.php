<?php
require_once('../connection/db.php');

session_start();
$invoiceId = $_POST['invoiceId'];

$sql = "SELECT `d`.`tbl_product_idtbl_product`, `d`.`idtbl_invoice_detail`, `p`.`product_name`, `d`.`saleprice`, `d`.`qty`, `i`.`idtbl_invoice` FROM `tbl_invoice` AS `i` LEFT JOIN `tbl_invoice_detail` AS `d` ON (`i`.`idtbl_invoice` = `d`.`tbl_invoice_idtbl_invoice`) LEFT JOIN `tbl_product` AS `p` ON (`p`.`idtbl_product` = `d`.`tbl_product_idtbl_product`) WHERE `i`.`idtbl_invoice` = '$invoiceId'";
$result = $conn->query($sql);
$array = array();


while ($row = $result->fetch_assoc()) { 
    array_push($array, array( "invoiceid" => $row['idtbl_invoice'], "productid" => $row['tbl_product_idtbl_product'], "invoicedetailid" => $row['idtbl_invoice_detail'], "productname" => $row['product_name'], "saleprice" => $row['saleprice'], "qty" => $row['qty']));
}

print(json_encode($array));

?>


