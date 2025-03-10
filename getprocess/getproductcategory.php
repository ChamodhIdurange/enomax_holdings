<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_product_category` WHERE `idtbl_product_category`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_product_category'];
$obj->category=$row['category'];

echo json_encode($obj);
?>