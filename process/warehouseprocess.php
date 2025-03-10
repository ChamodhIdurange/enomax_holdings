<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
}
require_once('../connection/db.php'); //die('bc');
$userID = $_SESSION['userid'];
$recordOption = $_POST['recordOption'];
$recordID = $_POST['recordID'];
$orderdate = $_POST['orderdate'];
$remark = $_POST['remark'];
$discountpresentage = $_POST['discountpresentage'];
$total = $_POST['total'];
$discount = $_POST['discount'];
$podiscountamount = $_POST['podiscountamount'];
$nettotal = $_POST['nettotal'];
$repname = $_POST['repname'];
$area = $_POST['area'];
//$location = $_POST['location'];
$customer = $_POST['customer'];
$paymentoption = $_POST['paymentoption'];
$tableData = $_POST['tableData'];
$podiscount = $_POST['podiscount'];
$tableData = json_decode($tableData);

$updatedatetime = date('Y-m-d h:i:s');

if ($recordOption == 1) {
    
} else {

    $month = date('n');

   // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $insretorder = "UPDATE `tbl_warehouse` SET `subtotal`='$total', `disamount`='$discount', `discount`='$discountpresentage', `podiscount`='$podiscount', `po_amount`='$podiscountamount', `nettotal`='$nettotal', `payfullhalf`='$paymentoption', `remark`='$remark', `updatedatetime`='$updatedatetime', `tbl_user_idtbl_user`='$userID' WHERE `tbl_porder_idtbl_porder` = '$recordID'";
    if ($conn->query($insretorder) == true) {

        // $insertporderother = "UPDATE `tbl_porder_otherinfo` SET `areaid`='$area', `customerid`='$customer', `repid`='$repname', `updatedatetime`='$updatedatetime' WHERE `porderid` ='$recordID'";
        // $conn->query($insertporderother);
       // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $deleterecode = " DELETE FROM `tbl_warehouse_details` WHERE `tbl_porder_idtbl_porder` = '$recordID'";
        $conn->query($deleterecode);
        foreach ($tableData as $rowtabledata) {
            //$productID = $rowtabledata->col_3;
            $product = $rowtabledata->col_3;
            $unitprice = $rowtabledata->col_4;
            $saleprice = $rowtabledata->col_5;
            $newqty = $rowtabledata->col_6;
            $freeprodcutid = $rowtabledata->col_8;
            $freeqty = $rowtabledata->col_9;
            $totqty = $rowtabledata->col_10;
            $total = $rowtabledata->col_12;
           // $id = $rowtabledata->col_17;
          // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $insertorderdetail="INSERT INTO `tbl_warehouse_details`(`type`, `qty`, `freeqty`, `freeproductid`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`) VALUES ('0','$newqty','$freeqty','$product','$unitprice','$saleprice','1','$updatedatetime','$userID','$recordID','$product')";
            $conn->query($insertorderdetail);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $updatereptarget="UPDATE `tbl_employee_target` SET `targetqtycomplete`=(`targetqtycomplete`+'$newqty') WHERE MONTH(`month`)='$month' AND `status`=1 AND `tbl_employee_idtbl_employee`='$repname' AND `tbl_product_idtbl_product`='$product'";
            $conn->query($updatereptarget);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $updatereptargetfree="UPDATE `tbl_employee_target` SET `targetqtycomplete`=(`targetqtycomplete`+'$freeqty') WHERE MONTH(`month`)='$month' AND `status`=1 AND `tbl_employee_idtbl_employee`='$repname' AND `tbl_product_idtbl_product`='$product'";
            $conn->query($updatereptargetfree);
        }

        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Update Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'primary';

        echo $actionJSON = json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';

        echo $actionJSON = json_encode($actionObj);
    }
}
