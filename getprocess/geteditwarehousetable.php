<?php
require_once('../connection/db.php');

$recordID = $_POST['recordID'];

$sql2 = "SELECT `tbl_warehouse_details`.`qty`, `tbl_warehouse_details`.`freeqty`, `tbl_warehouse_details`.`freeproductid`, `tbl_warehouse_details`.`unitprice`, `tbl_warehouse_details`.`idtbl_porder_detail`, `tbl_warehouse_details`.`saleprice`, `tbl_product`.`product_name`, `tbl_warehouse`.`subtotal` AS `subtotal`, `tbl_warehouse`.`potype`, `tbl_warehouse`.`orderdate`, `tbl_warehouse`.`disamount`,
`tbl_warehouse`.`nettotal`, `tbl_warehouse`.`remark`, `tbl_warehouse_details`.`tbl_product_idtbl_product`, `tbl_warehouse`.`tbl_locations_idtbl_locations` FROM `tbl_warehouse` 
         LEFT JOIN `tbl_warehouse_details` 
         ON (`tbl_warehouse_details`.`tbl_porder_idtbl_porder` = `tbl_warehouse`.`tbl_porder_idtbl_porder`)
         LEFT JOIN `tbl_product` 
         ON (`tbl_product`.`idtbl_product` = `tbl_warehouse_details`.`tbl_product_idtbl_product`) 
         WHERE `tbl_warehouse_details`.`tbl_porder_idtbl_porder`= '$recordID'";

$result2 = $conn->query($sql2);
$rows = array();

while ($row2 = $result2->fetch_assoc()) {
    $obj = new stdClass();
    $obj->idtbl_porder_detail = $row2['idtbl_porder_detail'];
    $obj->qty = $row2['qty'];
    $obj->freeqty = $row2['freeqty'];
    $obj->freeproductid = $row2['freeproductid'];
    $obj->unitprice = $row2['unitprice'];
    $obj->saleprice = $row2['saleprice'];
    $obj->potype = $row2['potype'];
    $obj->orderdate = $row2['orderdate'];
    $obj->subtotal = $row2['subtotal'];
    $obj->disamount = $row2['disamount'];
    $obj->nettotal = $row2['nettotal'];
    $obj->remark = $row2['remark'];
    $obj->product_name = $row2['product_name'];
    $obj->tbl_product_idtbl_product = $row2['tbl_product_idtbl_product'];
    $obj->tbl_locations_idtbl_locations = $row2['tbl_locations_idtbl_locations'];
    $rows[] = $obj;
}

echo json_encode($rows);
