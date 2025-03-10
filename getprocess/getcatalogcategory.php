<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_catalog_category` WHERE `idtbl_catalog_category`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_catalog_category'];
$obj->category=$row['category'];
$obj->sequence=$row['sequence'];
$obj->sizecategory=$row['tbl_size_categories_idtbl_size_categories'];

echo json_encode($obj);
?>