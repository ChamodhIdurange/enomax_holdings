<?php
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sql="SELECT `tbl_warehouse`.`orderdate`, `tbl_locations`.`locationname`, `tbl_locations`.`idtbl_locations`, `tbl_warehouse`.`subtotal`, `tbl_warehouse`.`disamount`, `tbl_warehouse`.`po_amount`, `tbl_warehouse`.`discount`, `tbl_warehouse`.`nettotal`, `tbl_warehouse`.`remark`, `tbl_area`.`area`, `tbl_area`.`idtbl_area`, `tbl_customer`.`name` AS `cusname`, `tbl_customer`.`idtbl_customer`, `tbl_employee`.`name` AS `repname`, `tbl_employee`.`idtbl_employee` FROM `tbl_warehouse` LEFT JOIN `tbl_porder_otherinfo` ON `tbl_porder_otherinfo`.`porderid`=`tbl_warehouse`.`tbl_porder_idtbl_porder` LEFT JOIN `tbl_locations` ON `tbl_locations`.`idtbl_locations`=`tbl_warehouse`.`tbl_locations_idtbl_locations` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_porder_otherinfo`.`areaid` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_porder_otherinfo`.`customerid` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_porder_otherinfo`.`repid` WHERE `tbl_warehouse`.`confirmstatus`=1 AND `tbl_warehouse`.`status`=1 AND `tbl_warehouse`.`tbl_porder_idtbl_porder`='$orderID'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$sqlinfo="SELECT `u`.*, `ua`.`product_name` AS `issueproduct`, `ub`.`product_name` AS `freeproduct` FROM `tbl_warehouse_details` AS `u` LEFT JOIN `tbl_product` AS `ua` ON `ua`.`idtbl_product`=`u`.`tbl_product_idtbl_product` LEFT JOIN `tbl_product` AS `ub` ON `ub`.`idtbl_product`=`u`.`freeproductid` WHERE `u`.`status`=1 AND `u`.`tbl_porder_idtbl_porder`='$orderID'";
$resultinfo=$conn->query($sqlinfo);
$detailarray=array();
while($rowinfo=$resultinfo->fetch_assoc()){
    $obj=new stdClass();
    $obj->productid=$rowinfo['tbl_product_idtbl_product'];
    $obj->product=$rowinfo['issueproduct'];
    $obj->qty=$rowinfo['qty'];
    if(!empty($rowinfo['freeproduct'])){$obj->freeproduct=$rowinfo['freeproduct'];}
    else{$obj->freeproduct='';}
    $obj->freeproductid=$rowinfo['freeproductid'];
    $obj->freeqty=$rowinfo['freeqty'];
    $obj->unitprice=$rowinfo['unitprice'];
    $obj->saleprice=$rowinfo['saleprice'];

    array_push($detailarray, $obj);
}

$objmain=new stdClass();
$objmain->orderdate=$row['orderdate'];
$objmain->orderdate=$row['orderdate'];
$objmain->subtotal=$row['subtotal']; 
$objmain->disamount=$row['disamount'];
$objmain->po_amount=$row['po_amount'];
$objmain->nettotal=$row['nettotal'];
$objmain->remark=$row['remark'];
$objmain->area=$row['area'];
$objmain->idtbl_locations=$row['idtbl_locations'];
$objmain->locationname=$row['locationname'];
$objmain->idtbl_area=$row['idtbl_area'];
$objmain->cusname=$row['cusname'];
$objmain->idtbl_customer=$row['idtbl_customer'];
$objmain->repname=$row['repname'];
$objmain->idtbl_employee=$row['idtbl_employee'];
$objmain->discount=$row['discount'];
$objmain->datainfo=$detailarray;

echo json_encode($objmain);