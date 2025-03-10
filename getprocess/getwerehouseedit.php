<?php
require_once('../connection/db.php');

$recordID = $_POST['recordID'];

$sql = "SELECT *, `tbl_area`.`idtbl_area`, `tbl_locations`.`idtbl_locations`, `tbl_porder_otherinfo`.`idtbl_porder_otherinfo` FROM `tbl_warehouse` LEFT JOIN `tbl_porder_otherinfo` ON (`tbl_porder_otherinfo`.`idtbl_porder_otherinfo` = `tbl_warehouse`.`tbl_porder_idtbl_porder`) LEFT JOIN `tbl_customer` ON (`tbl_customer`.`idtbl_customer` = `tbl_porder_otherinfo`.`customerid`) LEFT JOIN `tbl_area` ON (`tbl_area`.`idtbl_area` = `tbl_porder_otherinfo`.`areaid`) LEFT JOIN `tbl_employee` ON (`tbl_employee`.`idtbl_employee` = `tbl_porder_otherinfo`.`repid`) LEFT JOIN `tbl_locations` ON (`tbl_locations`.`idtbl_locations` = `tbl_warehouse`.`tbl_locations_idtbl_locations`) WHERE `tbl_porder_idtbl_porder`= '$recordID'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql2 = "SELECT `tbl_employee`.`idtbl_employee`, `tbl_area`.`idtbl_area`, `tbl_customer`.`idtbl_customer`, `tbl_customer`.`phone`, `tbl_customer`.`address`, `tbl_customer`.`name` FROM `tbl_porder_otherinfo` LEFT JOIN `tbl_employee` ON (`tbl_employee`.`idtbl_employee` = `tbl_porder_otherinfo`.`repid`) LEFT JOIN `tbl_area` ON (`tbl_area`.`idtbl_area` = `tbl_porder_otherinfo`.`areaid`) LEFT JOIN `tbl_customer` ON (`tbl_customer`.`idtbl_customer` = `tbl_porder_otherinfo`.`customerid`) WHERE `porderid`= '$recordID'";

$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

$sql3 = "SELECT `subtotal` FROM `tbl_warehouse` WHERE `tbl_porder_idtbl_porder`= '$recordID'";

$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();



$obj = new stdClass();
$obj->id = $row['tbl_porder_idtbl_porder'];
$obj->orderdate = $row['orderdate'];
$obj->discount = $row['discount'];
$obj->podiscount = $row['podiscount'];
$obj->name = $row2['idtbl_customer'];
$obj->name2 = $row2['name'];
$obj->phone = $row2['phone'];
$obj->address = $row2['address'];
$obj->idtbl_area = $row2['idtbl_area'];
$obj->idtbl_employee = $row2['idtbl_employee'];
$obj->idtbl_locations = $row['idtbl_locations'];
$obj->tbl_locations_idtbl_locations = $row['tbl_locations_idtbl_locations'];
$obj->idtbl_porder_otherinfo = $row['idtbl_porder_otherinfo'];
$obj->subtotal = $row3['subtotal'];
$obj->disamount = $row['disamount'];
$obj->nettotal=$row['nettotal'];
$obj->po_amount=$row['po_amount'];
$obj->remark=$row['remark'];
$obj->payfullhalf=$row['payfullhalf'];

echo json_encode($obj);
