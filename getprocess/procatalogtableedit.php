<?php
require_once('../connection/db.php');

$recordID = $_POST['recordID'];
$sql2 = "SELECT d.idtbl_catalog_details, c.tbl_catalog_category_idtbl_catalog_category, `d`.`product_name` AS `product_id`, e.category, `p`.`product_name` FROM tbl_catalog AS c LEFT JOIN tbl_catalog_details AS d ON d.tbl_catalog_idtbl_catalog = c.idtbl_catalog LEFT JOIN tbl_catalog_category AS e ON e.idtbl_catalog_category = c.tbl_catalog_category_idtbl_catalog_category LEFT JOIN `tbl_product` AS `p` ON `p`.`idtbl_product` = `d`.`product_name` WHERE c.idtbl_catalog ='$recordID'";

$result2 = $conn->query($sql2);
$rows = array();

while ($row2 = $result2->fetch_assoc()) {
    $obj = new stdClass();
    $obj->idtbl_catalog_details = $row2['idtbl_catalog_details'];
    $obj->tbl_catalog_category_idtbl_catalog_category = $row2['tbl_catalog_category_idtbl_catalog_category'];
    $obj->product_id = $row2['product_id'];
    $obj->product_name = $row2['product_name'];
    $obj->category = $row2['category'];
    $rows[] = $obj;
}

echo json_encode($rows);