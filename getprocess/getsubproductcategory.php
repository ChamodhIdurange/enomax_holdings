<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_sub_product_category` WHERE `idtbl_sub_product_category`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_sub_product_category'];
$obj->category=$row['category'];
$obj->maincategory=$row['tbl_product_category_idtbl_product_category'];

echo json_encode($obj);
?>