<?php
require_once('../connection/db.php');

$record = $_POST['recordID'];

$sql = "SELECT * FROM `tbl_catalog` WHERE `idtbl_catalog`='$record'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$obj = new stdClass();
 $obj->id = $row['idtbl_catalog'];
// $obj->uom = $row['uom'];
// $obj->group_type = $row['group_type'];
$obj->tbl_catalog_category_idtbl_catalog_category = $row['tbl_catalog_category_idtbl_catalog_category'];
// $obj->tbl_product_idtbl_product = $row['tbl_product_idtbl_product'];
echo json_encode($obj);
