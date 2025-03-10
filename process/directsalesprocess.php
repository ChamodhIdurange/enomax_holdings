<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$netqty=0;


//Below part is not completely don

$invoiceDeliveryDate=$_POST['deliveryDate'];
$refID=$_POST['salerep'];
$customerID=$_POST['customer'];
$nettotal=$_POST['total'];
$paymentoption=$_POST['paymentoption'];
$tableData=$_POST['tableData'];
// $vatoption=$_POST['vatoption'];
$locationID=$_POST['locations'];

$contact=$_POST['contact'];
$addresscus=$_POST['addresscus'];
$customername=$_POST['customername'];

$advancepayment=$_POST['advancepayment'];
$customerdiscount=$_POST['customerdiscount'];
$paymentdiscount=$_POST['paymentdiscount'];
$balanceamount=$_POST['hiddenbalance'];

$fulldiscount=$customerdiscount+$paymentdiscount;
$subtotal = $nettotal+$fulldiscount;

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

if(empty($_POST['customer'])){
    $insertuser="INSERT INTO `tbl_customer`(`name`, `phone`, `address`, `email`, `updatedatetime`, `tbl_user_idtbl_user`, `status`, `tbl_area_idtbl_area`) VALUES ('$customername','$contact', '$addresscus', '', '$updatedatetime', '$userID','1', '$locationID')";

    $conn->query($insertuser);
    $customerID=$conn->insert_id;
}

$insretinvoice="INSERT INTO `tbl_invoice`(`date`, `total`, `paymentmethod`, `paymentcomplete`, `chequesend`, `companydiffsend`, `ref_id`, `addtoaccountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`, `trackingnumber`, `deliverystatus`, `tbl_company_idtbl_company`, `qtycancelstatus`, `qtyreason`) VALUES ('$updatedatetime','$nettotal','0','0','0','0','$refID','0','1','$updatedatetime','$userID','$locationID','$customerID','1','0', '0', '1', '0', '')";


$insertporder="INSERT INTO `tbl_porder`(`potype`, `orderdate`, `subtotal`, `disamount`, `discount`, `nettotal`, `payfullhalf`, `remark`, `confirmstatus`, `dispatchissue`, `grnissuestatus`, `paystatus`, `shipstatus`, `deliverystatus`, `trackingno`, `trackingwebsite`, `callstatus`, `narration`, `cancelreason`, `returnstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('1','$updatedatetime','$subtotal','$fulldiscount','','$nettotal','$paymentoption','Direct Sale','0','0','0','0','0','0','','-','0','-','-','0','1','$updatedatetime','$userID')";


if($conn->query($insretinvoice)==true){
    $invoiceID=$conn->insert_id;


    if($conn->query($insertporder)==true){
        $porderId=$conn->insert_id;

        $insertporderinvoice="INSERT INTO `tbl_porder_invoice`(`date`, `remark`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_invoice_idtbl_invoice`) VALUES ('$updatedatetime','Direct Sale','1','$updatedatetime','$userID','$porderId','$invoiceID')";
        $conn->query($insertporderinvoice);

        $insertporderother="INSERT INTO `tbl_porder_otherinfo`(`porderid`, `mobileid`, `areaId`, `customerid`, `repid`, `status`, `updatedatetime`) VALUES ('$porderId','0','$locationID','$customerID','$refID','1','$updatedatetime')";
        $conn->query($insertporderother);



        $insertpayment="INSERT INTO `tbl_invoice_payment`(`date`, `payment`, `balance`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$today','$advancepayment','$balanceamount','1','$updatedatetime','$userID')";
        $conn->query($insertpayment);
        $paymentId=$conn->insert_id;

        $insertpaymenthastable="INSERT INTO `tbl_invoice_payment_has_tbl_invoice`(`tbl_invoice_payment_idtbl_invoice_payment`, `tbl_invoice_idtbl_invoice`, `payamount`, `fullstatus`, `halfstatus`) VALUES ('$paymentId','$invoiceID','$nettotal','1','0')";

        if($conn->query($insertpaymenthastable)==true){
            $paymentID=$conn->insert_id;


            foreach($tableData as $rowtabledata){
                $productID=$rowtabledata['col_2'];
                $unitprice=$rowtabledata['col_3'];
                $qty=$rowtabledata['col_4'];
                $discount=$rowtabledata['col_5'];
                $total=$rowtabledata['col_6'];
                $warrantyyears=$rowtabledata['col_8'];
                $warrantymonths=$rowtabledata['col_10'];
                $reducedqty = $qty;

                $insertinvoicedetail="INSERT INTO `tbl_invoice_detail`(`qty`, `freeqty`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_invoice_idtbl_invoice`) VALUES ('$qty', '0','$unitprice','$unitprice','1','$updatedatetime','$userID','$productID','$invoiceID')";
                $conn->query($insertinvoicedetail);

                $insertorderdetail="INSERT INTO `tbl_porder_detail`(`type`, `qty`, `freeqty`, `freeproductid`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`, `discountamount`) VALUES ('0','$qty','0','$productID','$unitprice','$unitprice','1','$updatedatetime','$userID','$porderId','$productID', '$discount')";
                $conn->query($insertorderdetail);

                // $insertinvoicedetail="INSERT INTO `tbl_invoice_detail`(`qty`, `freeqty`, `freeproductid`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_invoice_idtbl_invoice`) VALUES ('$newqty','$freeqty','$freeproductid','$unitprice','$saleprice','1','$updatedatetime','$userID','$product','$invoiceID')";
                // $conn->query($insertinvoicedetail);

                $getstock = "SELECT * FROM `tbl_stock` WHERE `qty` > 0 AND `tbl_product_idtbl_product` = '$productID' ORDER BY SUBSTRING(batchno, 4) ASC LIMIT 1";
                $result =$conn-> query($getstock); 
                $stockdata = $result-> fetch_assoc();

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
                
            }
        }


    }
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    $obj=new stdClass();
    $obj->action=json_encode($actionObj);
    $obj->actiontype='1';
    $obj->invoiceid=$invoiceID;

    echo json_encode($obj);

}else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    $obj=new stdClass();
    $obj->action=json_encode($actionObj);
    $obj->actiontype='0';
    $obj->invoiceid='0';

    echo json_encode($obj);
}

?>