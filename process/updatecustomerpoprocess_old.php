<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$today = date('Y-m-d'); 

$poID=$_POST['poID'];
$total=$_POST['total'];
$nettotal=$_POST['nettotal'];
$discount=$_POST['discount'];
$podiscountPrecentage=$_POST['podiscountPrecentage'];
$podiscountAmount=$_POST['podiscountAmount'];
$remarkVal=$_POST['remarkVal'];

$acceptanceType=$_POST['acceptanceType'];
$isChangeStatus=$_POST['isChangeStatus'];
$tableData=$_POST['tableData'];
$tableData = json_decode($tableData);

$updatedatetime=date('Y-m-d h:i:s');
$areaId=null;
$locationId=null;
$customerId=null;

$fullDiscount = $discount + $podiscountAmount;

if($acceptanceType == 1){
    // CONFIRMED
    $updatePoValues="UPDATE  `tbl_customer_order` SET `date`='$today', `podiscount`='$podiscountAmount', `podiscountpercentage`='$podiscountPrecentage', `discount`='$discount', `nettotal`='$nettotal', `total`='$total', `confrimuser`='$userID', `remark`='$remarkVal' WHERE `idtbl_customer_order`='$poID'";

    $updatePoStatus = "UPDATE  `tbl_customer_order` SET `confirm`='1' WHERE `idtbl_customer_order`='$poID'";

    $getporderdata = "SELECT * FROM `tbl_customer_order` WHERE `idtbl_customer_order` = '$poID'";
    $result = $conn->query($getporderdata);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $areaId = $row['tbl_area_idtbl_area'];
        $locationId = $row['tbl_locations_idtbl_locations'];
        $customerId = $row['tbl_customer_idtbl_customer'];

        if($isChangeStatus == 1){
            $insertInvoice="INSERT INTO `tbl_invoice` (`invoiceno`, `date`, `total`, `discount`, `vatamount`, `nettotal`, `paymentcomplete`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_locations_idtbl_locations`, `tbl_customer_order_idtbl_customer_order`) VALUES('-', '$updatedatetime', '$total', '$fullDiscount', '0', '$nettotal', '0', '1', '$updatedatetime', '$userID', '$areaId', '$customerId', '$locationId', '$poID')";
            $conn->query($insertInvoice);
            $invoiceId = $conn->insert_id;

            $dateformat = date('y/m/');
            $invoiceNo = 'IV/'. $dateformat . $invoiceId;

            $updateInvoiceNo="UPDATE `tbl_invoice` SET `invoiceno`='$invoiceNo' WHERE `idtbl_invoice` = '$invoiceId'";
            $conn->query($updateInvoiceNo);
        }
        
    } 
}else if($acceptanceType == 2){
    // DISPATCHED
    $updatePoValues="UPDATE  `tbl_customer_order` SET `podiscount`='$podiscountAmount', `podiscountpercentage`='$podiscountPrecentage',  `discount`='$discount',`nettotal`='$nettotal', `total`='$total', `dispatchuser`='$userID', `remark`='$remarkVal'  WHERE `idtbl_customer_order`='$poID'";

    $updatePoStatus = "UPDATE  `tbl_customer_order` SET `dispatchissue`='1' WHERE `idtbl_customer_order`='$poID'";

    $insertDispatch="INSERT INTO `tbl_cutomer_order_dispatch`(`dispatchdate`, `vehicleno`, `drivername`, `trackingno`, `trackingwebsite`, `currier`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES('$updatedatetime', '-', '-', '-', '-', '-', '1', '$updatedatetime', '$userID')";
    $conn->query($insertDispatch);
    $dispatchId = $conn->insert_id;
            
    $insertDispatchInfo="INSERT INTO `tbl_cutomer_order_dispatch_has_tbl_customer_order`(`tbl_cutomer_order_dispatch_idtbl_cutomer_order_dispatch`, `tbl_customer_order_idtbl_customer_order`) VALUES('$dispatchId', '$poID')";
    $conn->query($insertDispatchInfo);
}else if($acceptanceType == 3){
    // DELIVERED
    $updatePoValues="UPDATE  `tbl_customer_order` SET `podiscount`='$podiscountAmount', `podiscountpercentage`='$podiscountPrecentage',  `ship`='1', `discount`='$discount',`nettotal`='$nettotal', `total`='$total', `delivereduser`='$userID', `shipuser`='$userID', `remark`='$remarkVal'  WHERE `idtbl_customer_order`='$poID'";

    $updatePoStatus = "UPDATE  `tbl_customer_order` SET `delivered`='1' WHERE `idtbl_customer_order`='$poID'";

    $getinvoicedata = "SELECT * FROM `tbl_invoice` WHERE `tbl_customer_order_idtbl_customer_order` = '$poID'";
    $resultinvoice = $conn->query($getinvoicedata);

    if ($resultinvoice->num_rows > 0) {
        $rowinvoice = $resultinvoice->fetch_assoc();
        $invoiceId = $rowinvoice['idtbl_invoice'];
        $invoiceNo = $rowinvoice['invoiceno'];
    }

    $updateinvoicehead="UPDATE `tbl_invoice` SET `total` = '$total', `discount` = '$fullDiscount', `nettotal` = '$nettotal', `updatedatetime` = '$updatedatetime' WHERE `idtbl_invoice`='$invoiceId'";
    $conn->query($updateinvoicehead);
}

if($conn->query($updatePoValues)==true){
    if($isChangeStatus == 1){
        $conn->query($updatePoStatus);
    }

    foreach($tableData as $rowtabledata){
        $productID=$rowtabledata->col_3;
        $podetailId=$rowtabledata->col_4;
        $qty=$rowtabledata->col_5;
        $linediscountprecentage=$rowtabledata->col_6;
        $linediscountamount=$rowtabledata->col_7;
        $saleprice=$rowtabledata->col_9;
        $status=$rowtabledata->col_11;
        $fullTotal=$rowtabledata->col_12; 
        $newstatus=$rowtabledata->col_13; 

        $netTotal = $fullTotal - $linediscountamount;

        if($status == 3){
            $deleteHoldStock="UPDATE  `tbl_customer_order_hold_stock` SET `qty`='$qty', `status`='3', `invoiceissue`='1' WHERE `tbl_product_idtbl_product` = '$productID' AND `tbl_customer_order_idtbl_customer_order` = '$poID'";
            $conn->query($deleteHoldStock);
        }
        if($acceptanceType == 1){
            if($newstatus == 0){
                // This is to check whether poder status should be changed or now
                if($isChangeStatus == 1){
                    $updatePoDetail="UPDATE  `tbl_customer_order_detail` SET `confirmqty`='$qty', `discountpresent`='$linediscountprecentage', `discount`='$linediscountamount', `total`='$netTotal', `status`='$status', `saleprice` = '$saleprice' WHERE `idtbl_customer_order_detail` = '$podetailId'";
                }else{
                    $updatePoDetail="UPDATE  `tbl_customer_order_detail` SET `orderqty`='$qty', `discountpresent`='$linediscountprecentage', `discount`='$linediscountamount', `total`='$netTotal', `status`='$status', `saleprice` = '$saleprice' WHERE `idtbl_customer_order_detail` = '$podetailId'";
                }

                
                $conn->query($updatePoDetail);
                
                $updateHoldStock="UPDATE  `tbl_customer_order_hold_stock` SET `qty`='$qty', `status`='1' WHERE `tbl_product_idtbl_product` = '$productID' AND `tbl_customer_order_idtbl_customer_order` = '$poID'";
            }else{
                $getproductData = "SELECT `unitprice` FROM `tbl_product` WHERE `idtbl_product` = '$productID'";
                $resultproduct = $conn->query($getproductData);
                $productdata = $resultproduct->fetch_assoc();

                $unitprice = $productdata['unitprice'];

                $insertPoDetail="INSERT INTO `tbl_customer_order_detail`(`orderqty`, `total`, `confirmqty`, `dispatchqty`, `qty`, `unitprice`, `saleprice`, `discountpresent`, `discount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_order_idtbl_customer_order`, `tbl_product_idtbl_product`) VALUES ('$qty', '$netTotal', '$qty', '$qty', '$qty', '$unitprice','$saleprice', '$linediscountprecentage', '$linediscountamount', '1','$updatedatetime','$userID','$poID','$productID')";
                $conn->query($insertPoDetail);
    
                $updateHoldStock="INSERT INTO `tbl_customer_order_hold_stock`(`qty`, `invoiceissue`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_order_idtbl_customer_order`) VALUES ('$qty', '0', '1', '$updatedatetime', '$userID', '$productID','$poID')";
            }
            
        }else if($acceptanceType == 2){
            if($newstatus == 0){
                if($isChangeStatus == 1){
                    $updatePoDetail="UPDATE  `tbl_customer_order_detail` SET `dispatchqty`='$qty', `discountpresent`='$linediscountprecentage', `discount`='$linediscountamount', `total`='$netTotal', `status`='$status', `saleprice` = '$saleprice'  WHERE `idtbl_customer_order_detail` = '$podetailId'";
                }else{
                    $updatePoDetail="UPDATE  `tbl_customer_order_detail` SET `confirmqty`='$qty', `discountpresent`='$linediscountprecentage', `discount`='$linediscountamount', `total`='$netTotal', `status`='$status', `saleprice` = '$saleprice'  WHERE `idtbl_customer_order_detail` = '$podetailId'";
                }
                
                $conn->query($updatePoDetail);
    
                $updateHoldStock="UPDATE  `tbl_customer_order_hold_stock` SET `qty`='$qty', `status`='1' WHERE `tbl_product_idtbl_product` = '$productID' AND `tbl_customer_order_idtbl_customer_order` = '$poID'";
            }else{
                $getproductData = "SELECT `unitprice` FROM `tbl_product` WHERE `idtbl_product` = '$productID'";
                $resultproduct = $conn->query($getproductData);
                $productdata = $resultproduct->fetch_assoc();

                $unitprice = $productdata['unitprice'];

                $insertPoDetail="INSERT INTO `tbl_customer_order_detail`(`orderqty`, `total`, `confirmqty`, `dispatchqty`, `qty`, `unitprice`, `saleprice`, `discountpresent`, `discount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_order_idtbl_customer_order`, `tbl_product_idtbl_product`) VALUES ('$qty', '$netTotal', '$qty', '$qty', '$qty', '$unitprice','$saleprice', '$linediscountprecentage', '$linediscountamount', '1','$updatedatetime','$userID','$poID','$productID')";
                $conn->query($insertPoDetail);
    
                $updateHoldStock="INSERT INTO `tbl_customer_order_hold_stock`(`qty`, `invoiceissue`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_order_idtbl_customer_order`) VALUES ('$qty', '0', '1', '$updatedatetime', '$userID', '$productID','$poID')";
            }
        }else if($acceptanceType == 3){
            if($newstatus == 0){
                if($isChangeStatus == 1){
                    
                    $updatePoDetail="UPDATE  `tbl_customer_order_detail` SET `qty`='$qty', `discountpresent`='$linediscountprecentage', `discount`='$linediscountamount', `total`='$netTotal', `status`='$status', `saleprice` = '$saleprice'  WHERE `idtbl_customer_order_detail` = '$podetailId'";

                    $updateHoldStock="UPDATE  `tbl_customer_order_hold_stock` SET `qty`='$qty', `status`='3', `invoiceissue`='1' WHERE `tbl_product_idtbl_product` = '$productID' AND `tbl_customer_order_idtbl_customer_order` = '$poID'";
                }else{
                    $updatePoDetail="UPDATE  `tbl_customer_order_detail` SET `dispatchqty`='$qty', `discountpresent`='$linediscountprecentage', `discount`='$linediscountamount', `total`='$netTotal', `status`='$status', `saleprice` = '$saleprice'  WHERE `idtbl_customer_order_detail` = '$podetailId'";

                    $updateHoldStock="UPDATE  `tbl_customer_order_hold_stock` SET `qty`='$qty', `status`='1', `invoiceissue`='0' WHERE `tbl_product_idtbl_product` = '$productID' AND `tbl_customer_order_idtbl_customer_order` = '$poID'";
                }
                
                $conn->query($updatePoDetail);
    
            }else{
                $getproductData = "SELECT `unitprice` FROM `tbl_product` WHERE `idtbl_product` = '$productID'";
                $resultproduct = $conn->query($getproductData);
                $productdata = $resultproduct->fetch_assoc();

                $unitprice = $productdata['unitprice'];

                $insertPoDetail="INSERT INTO `tbl_customer_order_detail`(`orderqty`, `total`, `confirmqty`, `dispatchqty`, `qty`, `unitprice`, `saleprice`, `discountpresent`, `discount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_order_idtbl_customer_order`, `tbl_product_idtbl_product`) VALUES ('$qty', '$netTotal', '$qty', '$qty', '$qty', '$unitprice','$saleprice', '$linediscountprecentage', '$linediscountamount', '1','$updatedatetime','$userID','$poID','$productID')";
                $conn->query($insertPoDetail);
    
                $updateHoldStock="INSERT INTO `tbl_customer_order_hold_stock`(`qty`, `invoiceissue`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_order_idtbl_customer_order`) VALUES ('$qty', '0', '1', '$updatedatetime', '$userID', '$productID','$poID')";
            }
            
            $getporderdata = "SELECT * FROM `tbl_customer_order_detail` WHERE `idtbl_customer_order_detail` = '$podetailId' AND `status` = '1'";
            $result = $conn->query($getporderdata);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $unitprice = $row['unitprice'];
                $saleprice = $row['saleprice'];
                $lineTot = $row['total'];
                $discount = $row['discount'];

                if($isChangeStatus == 1){
                    $insertInvoideDetail="INSERT INTO `tbl_invoice_detail`(`qty`, `unitprice`, `saleprice`, `discount`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_invoice_idtbl_invoice`) VALUES('$qty', '$unitprice', '$saleprice', '$discount', '$lineTot', '1', '$updatedatetime', '$userID', '$productID', '$invoiceId')";
                    $conn->query($insertInvoideDetail);

                    // Stock Update
                    $productID = $row['tbl_product_idtbl_product'];
                    $reducedqty = $qty;
                    $freeproductid = 0;
                    $freeqty = 0;

                    $getstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$productID' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
                    $result = $conn->query($getstock);
                    $stockdata = $result->fetch_assoc();

                    $stockbatch = $stockdata['batchno'];
                    $batchqty = $stockdata['qty'];

                    while($batchqty < $reducedqty){
                        $updatestock="UPDATE `tbl_stock` SET `qty`=0 WHERE `tbl_product_idtbl_product`='$productID' AND `batchno` = '$stockbatch'";
                        $conn->query($updatestock);
            
                        $reducedqty = $reducedqty - $batchqty;
            
                        $regetstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$productID' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
                        $reresult =$conn-> query($regetstock); 
                        $restockdata = $reresult-> fetch_assoc();
            
                        $stockbatch = $restockdata['batchno'];
                        $batchqty = $restockdata['qty'];
            
                        if($batchqty > $reducedqty){
                            break;
                        }
                    }
                    $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`-'$reducedqty') WHERE `tbl_product_idtbl_product`='$productID' AND `batchno` = '$stockbatch'";
                    $conn->query($updatestock);
                
                    $updatestockfree="UPDATE `tbl_stock` SET `qty`=(`qty`-'$freeqty') WHERE `tbl_product_idtbl_product`='$freeproductid'";
                    $conn->query($updatestockfree);
                }
            } 
        }
        $conn->query($updateHoldStock);
    }
        
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Record Updated Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}
