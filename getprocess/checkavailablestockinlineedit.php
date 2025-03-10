<?php 
require_once('../connection/db.php');

$productID=$_POST['productID'];
$customerOrderId=$_POST['customerOrderId'];

$sqlstock="SELECT SUM(`qty`) as `qty` FROM `tbl_stock` WHERE `tbl_product_idtbl_product`='$productID' GROUP BY `tbl_product_idtbl_product`";
$result=$conn->query($sqlstock);
$row=$result->fetch_assoc();
$stockqty = $row['qty'];


$sqlholdstock="SELECT SUM(`qty`) as `qty` FROM `tbl_customer_order_hold_stock` WHERE `tbl_product_idtbl_product`='$productID' AND `status` = '1' AND `invoiceissue` = '0' GROUP BY `tbl_product_idtbl_product`";
$result=$conn->query($sqlholdstock);
$row=$result->fetch_assoc();
$holdqty = $row['qty'];

$sqlholdstockspare="SELECT `qty` FROM `tbl_customer_order_hold_stock` WHERE `tbl_product_idtbl_product`='$productID' AND `tbl_customer_order_idtbl_customer_order`='$customerOrderId' AND `status` = '1' AND `invoiceissue` = '0' GROUP BY `tbl_product_idtbl_product`";
$result=$conn->query($sqlholdstockspare);
$rowspare=$result->fetch_assoc();
$spareqty = $rowspare['qty'];

$obj=new stdClass();
$obj->availableqty=$stockqty + $spareqty - $holdqty;
$obj->stockqty=$stockqty;
$obj->holdqty=$holdqty;
$obj->spareqty=$spareqty;

echo json_encode($obj);
?>