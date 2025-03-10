<?php

require_once('dbConnect.php');

$refId="3";//$_POST['refId'];

$arrayinvoice=array();

$sqlvehcleload="SELECT * FROM `tbl_vehicle_load` WHERE `refid`='$refId' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
$resultvehcleload = mysqli_query($con, $sqlvehcleload);
$rowvehcleload = mysqli_fetch_row($resultvehcleload);

$idrootId = $rowvehcleload[12];

$sql = "SELECT `idtbl_invoice`,`date`,`total`,`tbl_customer_idtbl_customer`,`tbl_reject_reason_idtbl_reject_reason` FROM `tbl_invoice` WHERE `tbl_vehicle_load_idtbl_vehicle_load` IN (SELECT `idtbl_vehicle_load` FROM `tbl_vehicle_load` WHERE `tbl_area_idtbl_area`='$idrootId') ORDER BY `idtbl_invoice` DESC";
$res = mysqli_query($con, $sql);
$result = array();

while ($rowIn = mysqli_fetch_array($res)) {
    $invoId = $rowIn['idtbl_invoice'];
    $qslItem = "SELECT tblInv.*,tbl_product.product_name FROM((SELECT `newqty`,`refillqty`,`trustqty`,`returnqty`,`unitprice`,`newsaleprice`,`newrefillprice`,`cusfullqty`,`cusemptyqty`,`tbl_product_idtbl_product` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice`='$invoId') tblInv)INNER JOIN tbl_product ON tblInv.tbl_product_idtbl_product=tbl_product.idtbl_product";
    $resItem = mysqli_query($con, $qslItem);
    $invoicedetail = array();
    while ($row = mysqli_fetch_array($resItem)) {
        array_push($invoicedetail, array("newqty" => $row['newqty'], "refillqty" => $row['refillqty'],"trustqty" => $row['trustqty'], "returnqty" => $row['returnqty'],"unitprice" => $row['unitprice'], "newsaleprice" => $row['newsaleprice'],"newrefillprice" => $row['newrefillprice'], "cusfullqty" => $row['cusfullqty'],"cusemptyqty" => $row['cusemptyqty'],"proId" => $row['tbl_product_idtbl_product'],"proName"=>$row['product_name']));
    }

    $obj=new stdClass();
    $obj->invoiceID=$rowIn['idtbl_invoice'];
    $obj->date=$rowIn['date'];
    $obj->total=$rowIn['total'];
    $obj->customerID=$rowIn['tbl_customer_idtbl_customer'];
    $obj->rejectID=$rowIn['tbl_reject_reason_idtbl_reject_reason'];
    $obj->invoiceinfo=$invoicedetail;

    array_push($arrayinvoice, $obj);
}

echo json_encode($arrayinvoice);
