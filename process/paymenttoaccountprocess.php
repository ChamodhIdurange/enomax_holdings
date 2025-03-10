<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$company=$_POST['company'];
$companybranch=$_POST['companybranch'];
$creditaccount=$_POST['creditaccount'];
$debitaccount=$_POST['debitaccount'];
$narration=$_POST['narration'];
$porderpaymentID=$_POST['porderpaymentID'];

$updatedatetime=date('Y-m-d h:i:s');

$sqlcompany="SELECT `code` FROM `tbl_company` WHERE `idtbl_company`='$company' AND `status`=1";
$resultcompany=$conn->query($sqlcompany);
$rowcompany=$resultcompany->fetch_assoc();

$companycode=$rowcompany['code'];

$sqlbranch="SELECT `code` FROM `tbl_company_branch` WHERE `idtbl_company_branch`='$companybranch' AND `status`=1";
$resultbranch=$conn->query($sqlbranch);
$rowbranch=$resultbranch->fetch_assoc();

$branchcode=$rowbranch['code'];

$sqlaccountcredit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$creditaccount' AND `status`=1";
$resultaccountcredit=$conn->query($sqlaccountcredit);
$rowaccountcredit=$resultaccountcredit->fetch_assoc();

$accountcreditID=$rowaccountcredit['idtbl_subaccount'];

$sqlaccountdebit="SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$debitaccount' AND `status`=1";
$resultaccountdebit=$conn->query($sqlaccountdebit);
$rowaccountdebit=$resultaccountdebit->fetch_assoc();

$accountdebitID=$rowaccountdebit['idtbl_subaccount'];

$sqlporderpaymentinfo="SELECT `ordertotal`, `previousbill`, `balancetotal` FROM `tbl_porder_payment` WHERE `idtbl_porder_payment`='$porderpaymentID' AND `status`=1";
$resultporderpaymentinfo=$conn->query($sqlporderpaymentinfo);
$rowporderpaymentinfo=$resultporderpaymentinfo->fetch_assoc();

$prevoiustotal=$rowporderpaymentinfo['previousbill'];
$balancetotal=$rowporderpaymentinfo['balancetotal'];

$insretpayment="INSERT INTO `tbl_gl_payments`(`creditor_name`, `payment_head_narration`, `payment_credit_branch`, `payment_credit_account`, `payment_credit_branch_code`, `payment_credit_subaccount`, `payment_complete`, `refno`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('Laugfs Gas Company','$narration','$companybranch','$accountcreditID','$branchcode','$creditaccount','0','','$userID','','$updatedatetime','')";
if($conn->query($insretpayment)==true){
    $paymentID=$conn->insert_id;

    if($prevoiustotal>0){
        $insertpaymentinfoprevious="INSERT INTO `tbl_gl_payment_details`(`tbl_gl_payment_id`, `payment_sub_narration`, `payment_debit_branch`, `payment_debit_account`, `payment_debit_branch_code`, `payment_debit_subaccount`, `settle_by_cash`, `settle_by_cheque`, `cheque_no`, `cheque_date`, `cheque_bank`, `cheque_crossed`, `payment_invoice_no`, `paid_amount`, `porderpaymentid`, `payment_cancel`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('$paymentID','Create dateed cheque (21 Days)','$companybranch','$accountdebitID','$branchcode','$debitaccount','0','1','','','','','-','$prevoiustotal','$porderpaymentID','0','$userID','','$updatedatetime','')";
        $conn->query($insertpaymentinfoprevious);
    }

    $insertpaymentinfo="INSERT INTO `tbl_gl_payment_details`(`tbl_gl_payment_id`, `payment_sub_narration`, `payment_debit_branch`, `payment_debit_account`, `payment_debit_branch_code`, `payment_debit_subaccount`, `settle_by_cash`, `settle_by_cheque`, `cheque_no`, `cheque_date`, `cheque_bank`, `cheque_crossed`, `payment_invoice_no`, `paid_amount`, `porderpaymentid`, `payment_cancel`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('$paymentID','$narration','$companybranch','$accountdebitID','$branchcode','$debitaccount','0','1','','','','','-','$balancetotal','$porderpaymentID','0','$userID','','$updatedatetime','')";
    $conn->query($insertpaymentinfo);

    $updateporderstatus="UPDATE `tbl_porder_payment` SET `accountstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder_payment`='$porderpaymentID'";
    $conn->query($updateporderstatus);

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