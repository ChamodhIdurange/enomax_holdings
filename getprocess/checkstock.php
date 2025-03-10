<?php 
require_once('../connection/db.php');

$productid=$_POST['productid'];
$locationid=$_POST['locationid'];

$sql="SELECT SUM(`qty`) as `qty` FROM `tbl_stock` WHERE `tbl_product_idtbl_product`='$productid' GROUP BY `tbl_product_idtbl_product`";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->qty=$row['qty'];

echo json_encode($obj);
?>