<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$tableData=$_POST['tableData'];
$orderID=$_POST['orderID'];
$billtotal=$_POST['billtotal'];

$updatedatetime=date('Y-m-d h:i:s');

$sqlorderinfo="SELECT `nettotal` FROM `tbl_porder` WHERE `idtbl_porder`='$orderID' AND `status`=1";
$resultorderinfo=$conn->query($sqlorderinfo);
$roworderinfo=$resultorderinfo->fetch_assoc();

$ordertotal=$roworderinfo['nettotal'];
$baltotal=($ordertotal-$billtotal);

$today=date('Y-m-d');

$sqlcheck="SELECT COUNT(*) AS `count`, `idtbl_porder_payment` FROM `tbl_porder_payment` WHERE `tbl_porder_idtbl_porder`='$orderID'";
$resultcheck=$conn->query($sqlcheck);
$rowcheck=$resultcheck->fetch_assoc();

$porderpaymentID=$rowcheck['idtbl_porder_payment'];

if($rowcheck['count']>0){
    $insertpayment = "UPDATE `tbl_porder_payment` SET `date`='$today',`previousbill`='$billtotal',`balancetotal`='$baltotal',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_porder_idtbl_porder`='$orderID'";
}
else{
    $insertpayment = "INSERT INTO `tbl_porder_payment`(`date`, `ordertotal`, `previousbill`, `balancetotal`, `accountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`) VALUES ('$today','$ordertotal','$billtotal','$baltotal','0','1','$updatedatetime','$userID','$orderID')";
}

if($conn->query($insertpayment)==true){
    if($tableData!=''){
        foreach($tableData as $rowtabledata){
            $invoiceID=$rowtabledata['col_3'];

            $updateinvoice="UPDATE `tbl_invoice` SET `chequesend`='1' WHERE `idtbl_invoice`='$invoiceID'";
            $conn->query($updateinvoice);

            $insertpreviousinv="INSERT INTO `tbl_porder_payment_has_tbl_invoice`(`tbl_porder_payment_idtbl_porder_payment`, `tbl_invoice_idtbl_invoice`) VALUES ('$porderpaymentID','$invoiceID')";
            $conn->query($insertpreviousinv);
        }
    }

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}