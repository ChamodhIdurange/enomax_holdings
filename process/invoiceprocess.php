<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$netqty=0;

$invoicedate=$_POST['invoicedate'];
$refID=$_POST['refID'];
$vehicleloadID=$_POST['vehicleloadID'];
$areaID=$_POST['areaID'];
$customerID=$_POST['customerID'];
$nettotal=$_POST['nettotal'];
$tableData=$_POST['tableData'];

$invoicemonth = date("n", strtotime($invoicedate));

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$insertinvoice="INSERT INTO `tbl_invoice`(`date`, `total`, `paymentmethod`, `paymentcomplete`, `chequesend`, `companydiffsend`, `ref_id`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$invoicedate','$nettotal','0','0','0','0','$refID','1','$updatedatetime','$userID','$areaID','$customerID','$vehicleloadID')";
if($conn->query($insertinvoice)==true){
    $invoiceID=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $unitprice=$rowtabledata['col_3'];
        $refillprice=$rowtabledata['col_4'];
        $newsaleprice=$rowtabledata['col_5'];
        $refillsaleprice=$rowtabledata['col_6'];
        $newqty=$rowtabledata['col_7'];
        $refillqty=$rowtabledata['col_8'];
        $returnqty=$rowtabledata['col_9'];
        $trustqty=$rowtabledata['col_10'];
        $total=$rowtabledata['col_11'];

        $insertinvoicedetail="INSERT INTO `tbl_invoice_detail`(`newqty`, `refillqty`, `returnqty`, `trustqty`, `unitprice`, `refillprice`, `newsaleprice`, `newrefillprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_invoice_idtbl_invoice`) VALUES ('$newqty','$refillqty','$returnqty','$trustqty','$unitprice','$refillprice','$newsaleprice','$refillsaleprice','1','$updatedatetime','$userID','$product','$invoiceID')";
        $conn->query($insertinvoicedetail);

        $totqty=$newqty+$refillqty+$trustqty;

        $updatestock="UPDATE `tbl_vehicle_load_detail` SET `qty`=(`qty`-'$totqty') WHERE `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
        $conn->query($updatestock);

        if($trustqty>0 | $returnqty>0){
            $sqlcheckcus="SELECT COUNT(*) AS `count` FROM `tbl_cutomer_trustreturn` WHERE `tbl_customer_idtbl_customer`='' AND `tbl_product_idtbl_product`='' AND `status`=1";
            $resultcheckcus =$conn-> query($sqlcheckcus);
            $rowcheckcus = $resultcheckcus-> fetch_assoc();

            if($rowcheckcus['count']>0){
                $updatecustrust="UPDATE `tbl_cutomer_trustreturn` SET `trustqty`=(`trustqty`+'$trustqty'),`returnqty`=(`returnqty`+'$returnqty'),`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_customer_idtbl_customer`='$customerID' AND `tbl_product_idtbl_product`='$product'";
                $conn->query($updatecustrust);
            }
            else{
                $insertcustrust="INSERT INTO `tbl_cutomer_trustreturn`(`trustqty`, `returnqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$trustqty','$returnqty','1','$updatedatetime','$userID','$customerID','$product')";
                $conn->query($insertcustrust);
            }
        }

        //Target section
        $sqlcheckreftarget="SELECT COUNT(*) AS `count` FROM `tbl_employee_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$product' AND `tbl_employee_idtbl_employee`='$refID'";
        $resultcheckreftarget =$conn-> query($sqlcheckreftarget);
        $rowcheckreftarget = $resultcheckreftarget-> fetch_assoc();
        if($rowcheckreftarget['count']>0){
            $updatereftarget="UPDATE `tbl_employee_target` SET `targetcomplete`=(`targetcomplete`+$totqty) WHERE `tbl_employee_idtbl_employee`='$refID' AND `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$product'";
            $conn->query($updatereftarget);
        }

        $sqlcheckvehitarget="SELECT COUNT(*) AS `count` FROM `tbl_vehicle_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1)";
        $resultcheckvehitarget =$conn-> query($sqlcheckvehitarget);
        $rowcheckvehitarget = $resultcheckvehitarget-> fetch_assoc();
        if($rowcheckvehitarget['count']>0){
            $updatevehicletarget="UPDATE `tbl_vehicle_target` SET `targetcomplete`=(`targetcomplete`+$totqty) WHERE `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1)";
            $conn->query($updatevehicletarget);
        }
    }

    $arrayinvoicelist=array();
    $sqlinovicelist="SELECT `idtbl_invoice`, `total`, `paymentcomplete` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invoicedate' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
    $resultinovicelist =$conn-> query($sqlinovicelist);
    while($rowinovicelist = $resultinovicelist-> fetch_assoc()){
        $objinvoice=new stdClass();
        $objinvoice->invoiceid=$rowinovicelist['idtbl_invoice'];
        $objinvoice->invoicetotal=$rowinovicelist['total'];
        $objinvoice->paystatus=$rowinovicelist['paymentcomplete'];

        array_push($arrayinvoicelist, $objinvoice);
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
    $obj->invicelist=$arrayinvoicelist;
    $obj->actiontype='1';

    echo json_encode($obj);
}
else{
    $arrayinvoicelist=array();
    $sqlinovicelist="SELECT `idtbl_invoice`, `total` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invoicedate' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
    $resultinovicelist =$conn-> query($sqlinovicelist);
    while($rowinovicelist = $resultinovicelist-> fetch_assoc()){
        $objinvoice=new stdClass();
        $objinvoice->invoiceid=$rowinovicelist['idtbl_invoice'];
        $objinvoice->invoicetotal=$rowinovicelist['total'];

        array_push($arrayinvoicelist, $objinvoice);
    }

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    $obj=new stdClass();
    $obj->action=json_encode($actionObj);
    $obj->invicelist=$arrayinvoicelist;
    $obj->actiontype='0';

    echo json_encode($obj);
}