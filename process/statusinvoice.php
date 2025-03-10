<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:../index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$record=$_GET['record'];
$type=$_GET['type'];

$updatedatetime=date('Y-m-d h:i:s');

if($type==3){
    $reason=$_GET['reason'];
    $sqldelete="UPDATE `tbl_invoice` SET `status`='3',`tbl_user_idtbl_user`='$userID', `invoice_cancel_reason` = '$reason', `updatedatetime`='$updatedatetime'  WHERE `idtbl_invoice`='$record'";
    if($conn->query($sqldelete)==true){
        header("Location:../invoiceview.php?action=3");
    }
}else{
    $sqlinvoice="SELECT `date`, `ref_id`, `tbl_vehicle_load_idtbl_vehicle_load`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `idtbl_invoice`='$record'";
    $resultinvoice =$conn-> query($sqlinvoice);
    $rowinvoice = $resultinvoice-> fetch_assoc();
    $invoicemonth = date("n", strtotime($rowinvoice['date']));
    
    $refID=$rowinvoice['ref_id'];
    $vehicleloadID=$rowinvoice['tbl_vehicle_load_idtbl_vehicle_load'];
    $cusID=$rowinvoice['tbl_customer_idtbl_customer'];
    
    $sql="UPDATE `tbl_invoice` SET `status`='$value',`tbl_user_idtbl_user`='$userID',`updatedatetime`='$updatedatetime'  WHERE `idtbl_invoice`='$record'";
    if($conn->query($sql)==true){
        $sqlcheckdetail="SELECT * FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_invoice_idtbl_invoice`='$record'";
        $resultcheckdetail =$conn-> query($sqlcheckdetail); 
        while($rowcheckdetail = $resultcheckdetail-> fetch_assoc()){
            $productID=$rowcheckdetail['tbl_product_idtbl_product'];
            $newqty=$rowcheckdetail['newqty'];
            $refillqty=$rowcheckdetail['refillqty'];
            $trustqty=$rowcheckdetail['trustqty'];
            $returnqty=$rowcheckdetail['returnqty'];
    
            $invdetailID=$rowcheckdetail['idtbl_invoice_detail'];
    
            $totqty=$newqty+$refillqty+$trustqty;
    
            $updatestock="UPDATE `tbl_vehicle_load_detail` SET `qty`=(`qty`+'$totqty') WHERE `tbl_product_idtbl_product`='$productID' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
            $conn->query($updatestock);
    
            if($trustqty>0 | $returnqty>0){
                $sqlcheckcus="SELECT COUNT(*) AS `count` FROM `tbl_cutomer_trustreturn` WHERE `tbl_customer_idtbl_customer`='$cusID' AND `tbl_product_idtbl_product`='$productID' AND `status`=1";
                $resultcheckcus =$conn-> query($sqlcheckcus);
                $rowcheckcus = $resultcheckcus-> fetch_assoc();
    
                if($rowcheckcus['count']>0){
                    $updatecustrust="UPDATE `tbl_cutomer_trustreturn` SET `trustqty`=(`trustqty`-'$trustqty'),`returnqty`=(`returnqty`-'$returnqty'),`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_customer_idtbl_customer`='$cusID' AND `tbl_product_idtbl_product`='$productID'";
                    $conn->query($updatecustrust);
                }
            }
    
            //Target section
            $sqlcheckreftarget="SELECT COUNT(*) AS `count` FROM `tbl_employee_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_employee_idtbl_employee`='$refID'";
            $resultcheckreftarget =$conn-> query($sqlcheckreftarget);
            $rowcheckreftarget = $resultcheckreftarget-> fetch_assoc();
            if($rowcheckreftarget['count']>0){
                $updatereftarget="UPDATE `tbl_employee_target` SET `targetcomplete`=(`targetcomplete`-$totqty) WHERE `tbl_employee_idtbl_employee`='$refID' AND `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$productID'";
                $conn->query($updatereftarget);
            }
    
            $sqlcheckvehitarget="SELECT COUNT(*) AS `count` FROM `tbl_vehicle_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1)";
            $resultcheckvehitarget =$conn-> query($sqlcheckvehitarget);
            $rowcheckvehitarget = $resultcheckvehitarget-> fetch_assoc();
            if($rowcheckvehitarget['count']>0){
                $updatevehicletarget="UPDATE `tbl_vehicle_target` SET `targetcomplete`=(`targetcomplete`-$totqty) WHERE `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$productID' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1)";
                $conn->query($updatevehicletarget);
            }
    
            $updateinvoicedetail="UPDATE `tbl_invoice_detail` SET `status`='$value',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice_detail`='$invdetailID'";
            $conn->query($updateinvoicedetail);
        }
    
        header("Location:../invoiceview.php?action=$type");
    }
    else{header("Location:../invoiceview.php?action=5");}
}

?>