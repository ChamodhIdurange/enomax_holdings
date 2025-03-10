<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
}
require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$orderdate = $_POST['orderdate'];
$total = $_POST['total'];
$remark = $_POST['remark'];
$repname = $_POST['repname'];
$area = $_POST['area'];
$location = $_POST['location'];
$customer = $_POST['customer'];
$orderID = $_POST['orderID'];
// $companyId=$_POST['companyId'];
// $trackingnumber=$_POST['trackingnumber'];
$tableData = $_POST['tableData'];
$tableData = json_decode($tableData);
$newbatchqty =0;
$updatedatetime = date('Y-m-d h:i:s');
$invoicedate = date('Y-m-d');

$insretinvoice = "INSERT INTO `tbl_invoice`(`date`, `total`, `paymentmethod`, `paymentcomplete`, `chequesend`, `companydiffsend`, `ref_id`, `addtoaccountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`, `trackingnumber`, `deliverystatus`, `tbl_company_idtbl_company`, `qtycancelstatus`, `qtyreason`, `tbl_locations_idtbl_locations`) VALUES ('$invoicedate','$total','0','0','0','0','$repname','0','1','$updatedatetime','$userID','$area','$customer','1','0', '0', '1', '0', '', '$location')";
if ($conn->query($insretinvoice) == true) {
    $invoiceID = $conn->insert_id;

    $insertorderinvoice = "INSERT INTO `tbl_porder_invoice`(`date`, `remark`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_invoice_idtbl_invoice`) VALUES ('$invoicedate','$remark','1','$updatedatetime','$userID','$orderID','$invoiceID')";
    $conn->query($insertorderinvoice);

    foreach ($tableData as $rowtabledata) {
        $product = $rowtabledata->col_2;
        $unitprice = $rowtabledata->col_3;
        $saleprice = $rowtabledata->col_4;
        $newqty = $rowtabledata->col_5;
        $reducedqty = $newqty;
        $freeproductid = $rowtabledata->col_7;
        $freeqty = $rowtabledata->col_8;
        $totqty = $rowtabledata->col_9;
        $total = $rowtabledata->col_11;
    
        // $getstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$product' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
        // $result = $conn->query($getstock);
        // $stockdata = $result->fetch_assoc();

        // $stockbatch = $stockdata['batchno'];
        // $batchqty = $stockdata['qty'];
       
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $insertinvoicedetail="INSERT INTO `tbl_invoice_detail`(`qty`, `freeqty`, `freeproductid`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_invoice_idtbl_invoice`) VALUES ('$newqty','$freeqty','$freeproductid','$unitprice','$saleprice','1','$updatedatetime','$userID','$product','$invoiceID')";
        $conn->query($insertinvoicedetail);

        // $reducedqty = $reducedqty - $batchqty;

        // while($batchqty < $reducedqty){
        //     $updatestock="UPDATE `tbl_stock` SET `qty`=0 WHERE `tbl_product_idtbl_product`='$product' AND `batchno` = '$stockbatch'";
        //     $conn->query($updatestock);

        //     $reducedqty = $reducedqty - $batchqty;

        //     $regetstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$product' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
        //     $reresult =$conn-> query($regetstock); 
        //     $restockdata = $reresult-> fetch_assoc();

        //     $stockbatch = $restockdata['batchno'];
        //     $batchqty = $restockdata['qty'];

        //     if($batchqty > $reducedqty){
        //         break;
        //     }
        // }
        // // echo $reducedqty;

        // $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`-'$reducedqty') WHERE `tbl_product_idtbl_product`='$product' AND `batchno` = '$stockbatch'";
        // $conn->query($updatestock);
       
        // $updatestockfree="UPDATE `tbl_stock` SET `qty`=(`qty`-'$freeqty') WHERE `tbl_product_idtbl_product`='$freeproductid'";
        // $conn->query($updatestockfree);
    }

    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-check-circle';
    $actionObj->title = '';
    $actionObj->message = 'Invoice Created Successfully';
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
