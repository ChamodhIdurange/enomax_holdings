<?php

require_once('dbConnect.php');

$orderData = $_POST['data'];
$userId = $_POST['refId'];
$rootId = $_POST['rootId'];
$updatedatetime = date('Y-m-d h:i:s');
$flag = true;
$con->autocommit(FALSE);

$headerInvoice = json_decode($orderData);

foreach ($headerInvoice as $hino) {

    $customerId = $hino->customerId;
    $docId = $hino->docId;
    $orderDate = $hino->orderDate;
    $remark = $hino->remark;
    $total = $hino->total;
    $subTotal = $hino->netTotal;
    $discount = $hino->discountPer;
    $disamount = $hino->discount;
    $payfullhalf = $hino->payType;
    $itemArray = $hino->arrayList;
    $totqty = 0;

 $sql = "INSERT INTO `tbl_porder`(`potype`, `orderdate`,`subtotal`, `disamount`, `discount`, `nettotal`,`payfullhalf`,`remark`, `confirmstatus`, `dispatchissue`, `grnissuestatus`, `paystatus`, `shipstatus`, `deliverystatus`, `trackingno`, `trackingwebsite`, `callstatus`, `narration`, `cancelreason`, `returnstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES('1','$orderDate','$subTotal','$disamount','$discount','$total','$payfullhalf','$remark','0','0','0','0','0','0','','-','0','0','0','0','1','$updatedatetime','$userId')";
    
    $result = mysqli_query($con, $sql);
    $last_id = $con->insert_id;

    if (!$result) {
        $flag = false;
    }

    foreach ($itemArray as $indet) {
        $freeproductid = $indet->freeproductid;
        $freeqty = $indet->freeqty;
        $itemNo = $indet->itemNo;
        $price = $indet->price;
        $qty = $indet->qty;
        $total = $indet->total;

        $sqlItem = "INSERT INTO `tbl_porder_detail`(`type`, `qty`, `freeqty`, `freeproductid`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`) VALUES ('0','$qty','$freeqty','$freeproductid','$price','$price','1','$updatedatetime','$userId','$last_id','$itemNo')";
        $resultItem = mysqli_query($con, $sqlItem);

        if (!$resultItem) {
            $flag = false;
        }
    }

    $sqlGetempId="SELECT `idtbl_employee` FROM tbl_employee WHERE `tbl_user_idtbl_user`='$userId'";
    $resultEmp = mysqli_query($con, $sqlGetempId);
    $rowEmpId = mysqli_fetch_row($resultEmp);
    $idEmp = $rowEmpId[0];

    $sqlOtherInfo = "INSERT INTO `tbl_porder_otherinfo`( `porderid`, `mobileid`, `areaid`, `customerid`, `repid`, `status`, `updatedatetime`) VALUES 
    ('$last_id','$docId','$rootId','$customerId','$idEmp','1','$updatedatetime')";
    $resultItemInfo = mysqli_query($con, $sqlOtherInfo);
    if (!$resultItemInfo) {
        $flag = false;
    }
}


if ($flag) {
    $con->commit();
    $response = array("code" => '200', "message" => 'Update Complete');
    print_r(json_encode($response));
} else {
    $con->rollback();
    $response = array("code" => '500', "message" => 'Update Not Complete');
    print_r(json_encode($response));
}
?>
