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
$location = $_POST['location'];
$customer = $_POST['customer'];
// $paymentoption = $_POST['paymentoption'];
$tableData = $_POST['tableData'];
$podiscount = $_POST['podiscount'];
$tableData = json_decode($tableData);

$updatedatetime = date('Y-m-d h:i:s');

// podiscount
// podiscountamount

$query = "SELECT MAX(idtbl_customer_order) AS max_id FROM tbl_customer_order";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $next_id = $row['max_id'] + 1;
} else {
    $next_id = 1;
}

$dateformat = date('y/m/');
$cuspono = 'CP/'. $dateformat . $next_id;

if ($recordOption == 1) {
    if ($_POST['directcustomer'] != '') {
        $directcustomer =  $_POST['directcustomer'];
        $customercontact =  $_POST['customercontact'];
        $customeraddress =  $_POST['customeraddress'];

        $insertcustomer = "INSERT INTO `tbl_customer`(`type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit`, `credittype`, `creditperiod`, `emergencydate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('1', '$directcustomer', '', '$customercontact', '', '$customeraddress', '', '', 0, 0, 0, 0,'','1','$updatedatetime', '$userID', '1')";
        $conn->query($insertcustomer);

        $customer = $conn->insert_id;
    }
    $month = date('n');


    $insretorder = "INSERT INTO `tbl_customer_order`(`cuspono`, `date`, `total`, `discount`, `podiscount`, `podiscountpercentage`, `vat`, `nettotal`, `remark`, `vatpre`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_employee_idtbl_employee`, `tbl_locations_idtbl_locations`, `tbl_customer_idtbl_customer`) VALUES ('$cuspono', '$orderdate','$total','$discount', '$podiscountamount', '$podiscount', '0', '$nettotal', '$remark', '0','1', '$updatedatetime', '$userID', '$area', '$repname', '$location' , '$customer')";

    if ($conn->query($insretorder) == true) {
        $orderID = $conn->insert_id;

        $insretoriginalorder = "INSERT INTO `tbl_original_customer_order`(`cuspono`, `date`, `total`, `discount`, `podiscount`, `podiscountpercentage`, `vat`, `nettotal`, `remark`, `vatpre`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_employee_idtbl_employee`, `tbl_locations_idtbl_locations`, `tbl_customer_idtbl_customer`, `tbl_customer_order_idtblcustomer_order`) VALUES ('$cuspono', '$orderdate','$total','$discount', '$podiscountamount', '$podiscount', '0', '$nettotal', '$remark', '0','1', '$updatedatetime', '$userID', '$area', '$repname', '$location' , '$customer', '$orderID')";
        $conn->query($insretoriginalorder);
        $originalOrderID = $conn->insert_id;

        foreach ($tableData as $rowtabledata) {
            $productCount = $rowtabledata->col_1;
            $product = $rowtabledata->col_3;
            $unitprice = $rowtabledata->col_4;
            $saleprice = $rowtabledata->col_5;
            $newqty = $rowtabledata->col_6;
            $freeprodcutid = $rowtabledata->col_8;
            $freeqty = $rowtabledata->col_9;
            $totqty = $newqty + $freeqty;
            $linediscount = $rowtabledata->col_12;
            $total = $rowtabledata->col_13;
            $fulltotwithoutdiscount = $rowtabledata->col_14;

            $linediscountpercentage = ($linediscount * 100) / $fulltotwithoutdiscount;

            $insertorderdetail = "INSERT INTO `tbl_customer_order_detail`(`orderqty`, `total`, `confirmqty`, `dispatchqty`, `qty`, `unitprice`, `saleprice`, `discountpresent`, `discount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_order_idtbl_customer_order`, `tbl_product_idtbl_product`) VALUES ('$newqty', '$total', '$newqty', '$newqty', '$newqty', '$unitprice','$saleprice', '$linediscountpercentage', '$linediscount', '1','$updatedatetime','$userID','$orderID','$product')";
            $conn->query($insertorderdetail);

            $insertoriginalorderdetail = "INSERT INTO `tbl_original_customer_order_detail`(`orderqty`, `total`, `confirmqty`, `dispatchqty`, `qty`, `unitprice`, `saleprice`, `discountpresent`, `discount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_original_customer_order_idtbl_original_customer_order`, `tbl_product_idtbl_product`) VALUES ('$newqty', '$total', '$newqty', '$newqty', '$newqty', '$unitprice','$saleprice', '$linediscountpercentage', '$linediscount', '1','$updatedatetime','$userID','$originalOrderID','$product')";
            $conn->query($insertoriginalorderdetail);

            $insertholdstock = "INSERT INTO `tbl_customer_order_hold_stock`(`qty`, `invoiceissue`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_order_idtbl_customer_order`) VALUES ('$newqty', '0', '1', '$updatedatetime', '$userID', '$product','$orderID')";
            $conn->query($insertholdstock);
        }

        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Add Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';

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
// else {

//     if ($_POST['directcustomer'] != '') {
//         $directcustomer =  $_POST['directcustomer'];
//         $customercontact =  $_POST['customercontact'];
//         $customeraddress =  $_POST['customeraddress'];

//         $insertcustomer = "INSERT INTO `tbl_customer`(`type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit`, `credittype`, `creditperiod`, `emergencydate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('1', '$directcustomer', '', '$customercontact', '', '$customeraddress', '', '', 0, 0, 0, 0,'','1','$updatedatetime', '$userID', '1')";
//         $conn->query($insertcustomer);

//         $customer = $conn->insert_id;
//     }

//     $month = date('n');


//     $insretorder = "UPDATE `tbl_porder` SET `orderdate`='$orderdate', `subtotal`='$total', `disamount`='$discount', `discount`='$discountpresentage', `podiscount`='$podiscount', `po_amount`='$podiscountamount', `nettotal`='$nettotal', `payfullhalf`='$paymentoption', `remark`='$remark', `updatedatetime`='$updatedatetime', `tbl_user_idtbl_user`='$userID', `tbl_locations_idtbl_locations`='$location' WHERE `idtbl_porder` = '$recordID'";
//     if ($conn->query($insretorder) == true) {

//         $insertporderother = "UPDATE `tbl_porder_otherinfo` SET `areaid`='$area', `customerid`='$customer', `repid`='$repname', `updatedatetime`='$updatedatetime' WHERE `porderid` ='$recordID'";
//         $conn->query($insertporderother);
//        // $deleterecode = " DELETE FROM `tbl_porder_detail` WHERE `tbl_porder_idtbl_porder` = '$recordID'";

//         foreach ($tableData as $rowtabledata) {
//             $productID = $rowtabledata->col_1;
//             $product = $rowtabledata->col_3;
//             $unitprice = $rowtabledata->col_4;
//             $saleprice = $rowtabledata->col_5;
//             $newqty = $rowtabledata->col_6;
//             $freeprodcutid = $rowtabledata->col_8;
//             $freeqty = $rowtabledata->col_9;
//             $totqty = $rowtabledata->col_10;
//             $total = $rowtabledata->col_12;
//             $id = $rowtabledata->col_17;

//             $insertorderdetail = "UPDATE `tbl_porder_detail` SET `type`='0', `qty`='$newqty', `freeqty`='$freeqty', `freeproductid`='$freeprodcutid', `unitprice`='$unitprice', `saleprice`='$saleprice', `status`='1', `updatedatetime`='$updatedatetime', `tbl_user_idtbl_user`='$userID', `tbl_product_idtbl_product`='$product' WHERE `idtbl_porder_detail`='$id'";
//             $conn->query($insertorderdetail);

//         }

//         $actionObj = new stdClass();
//         $actionObj->icon = 'fas fa-check-circle';
//         $actionObj->title = '';
//         $actionObj->message = 'Update Successfully';
//         $actionObj->url = '';
//         $actionObj->target = '_blank';
//         $actionObj->type = 'primary';

//         echo $actionJSON = json_encode($actionObj);
//     } else {
//         $actionObj = new stdClass();
//         $actionObj->icon = 'fas fa-exclamation-triangle';
//         $actionObj->title = '';
//         $actionObj->message = 'Record Error';
//         $actionObj->url = '';
//         $actionObj->target = '_blank';
//         $actionObj->type = 'danger';

//         echo $actionJSON = json_encode($actionObj);
//     }
// }
