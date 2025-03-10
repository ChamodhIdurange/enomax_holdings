<?php 
session_start();

$field_error_msg='';

if(!isset($_SESSION['userid'])){
	$field_error_msg='Session Expired';
}

if(!isset($_POST['debit_branch_colcode'], $_POST['debit_acc_colcode'], 
			$_POST['credit_branch_colcode'], $_POST['credit_acc_colcode'])){
	$field_error_msg='Select all fields marked as required';
}

if($field_error_msg!=''){
	//header ("Location:index.php");
	$actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message=$field_error_msg;//'Session Expired';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo json_encode(array('msgdesc'=>$actionObj));
	
	die();
}

require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$paymentCreditor=$_POST['pay_creditor'];
$headNarration=$_POST['creditor_narration'];

$debitBranch=$_POST['debit_branch'];
$debitAccount=$_POST['debit_acc'];
$debitBranchColcode=$_POST['debit_branch_colcode'];
$debitAccountColcode=$_POST['debit_acc_colcode'];
$creditBranch=$_POST['credit_branch'];
$creditAccount=$_POST['credit_acc'];
$creditBranchColcode=$_POST['credit_branch_colcode'];
$creditAccountColcode=$_POST['credit_acc_colcode'];

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/
$sub_k = '';
$part_k = '';


if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}

if(isset($_POST['ref_op'])){
	$sub_k = $_POST['ref_op'];
}

$paymentNarration=$_POST['pay_narration'];
$paidAmount=$_POST['pay_amount'];
$invoiceNo=$_POST['invoice_no'];

$setCash=(int)$_POST['set_cash'];
$setCheque=1-$setCash;


$conn->autocommit(FALSE);
$flag = true;

if($head_k==''){
	$updateSQL = "INSERT INTO tbl_gl_payments (creditor_name, payment_head_narration, payment_credit_branch, payment_credit_account, payment_credit_branch_code, payment_credit_subaccount, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("sssssss", $paymentCreditor, $headNarration, $creditBranch, $creditAccount, $creditBranchColcode, $creditAccountColcode, $userID);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==1){
		$head_k = $stmt->insert_id;
	}else{
		$flag = false;
	}
	
	$stmt->close();
	
}else{
	$updateSQL = "UPDATE tbl_gl_payments SET creditor_name=?, payment_head_narration=?, payment_credit_branch=?, payment_credit_account=?, payment_credit_branch_code=?, payment_credit_subaccount=?, updated_by=?, updated_at=NOW() WHERE id=? AND payment_complete=0";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ssssssss", $paymentCreditor, $headNarration, $creditBranch, $creditAccount, $creditBranchColcode, $creditAccountColcode, $userID, $head_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	$stmt->close();
}

if($sub_k==''){
	$updateSQL = "INSERT INTO tbl_gl_payment_details (tbl_gl_payment_id, payment_sub_narration, payment_debit_branch, payment_debit_account, payment_debit_branch_code, payment_debit_subaccount, settle_by_cash, settle_by_cheque, payment_invoice_no, paid_amount, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, '-', ?, ?, NOW())";
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ssssssssss", $head_k, $paymentNarration, $debitBranch, $debitAccount, $debitBranchColcode, $debitAccountColcode, $setCash, $setCheque, $paidAmount, $userID);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==1){
		$sub_k = $stmt->insert_id;
	}else{
		$flag = false;
	}
	
	$stmt->close();
	
}else{
	$updateSQL = "UPDATE tbl_gl_payment_details SET payment_sub_narration=?, payment_debit_branch=?, payment_debit_account=?, payment_debit_branch_code=?, payment_debit_subaccount=?, paid_amount=(paid_amount+?), updated_by=?, updated_at=NOW() WHERE id=?";
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ssssssss", $paymentNarration, $debitBranch, $debitAccount, $debitBranchColcode, $debitAccountColcode, $paidAmount, $userID, $sub_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	$stmt->close();
	
}

$updateSQL = "INSERT INTO tbl_gl_payment_detail_particulars (tbl_gl_payment_detail_id, tbl_gl_payment_id, payment_particulars, paid_amount, created_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($updateSQL);
$stmt->bind_param("sssss", $sub_k, $head_k, $invoiceNo, $paidAmount, $userID);
$ResultOut = $stmt->execute();

$affectedRowCnt = $conn->affected_rows;

if($affectedRowCnt==1){
	$part_k = $stmt->insert_id;
}else{
	$flag = false;
}

$actionObj=new stdClass();

if ($flag) {
	$conn->commit();
	/*
	echo "All queries were executed successfully";
	*/
	$actionObj->icon='fas fa-check-circle';
	$actionObj->title='';
	$actionObj->message='Add Successfully';
	$actionObj->url='';
	$actionObj->target='_blank';
	$actionObj->type='success';
} else {
	$conn->rollback();
	/*
	echo "All queries were rolled back";
	*/
	$actionObj->icon='fas fa-exclamation-triangle';
	$actionObj->title='';
	$actionObj->message='Record Error';
	$actionObj->url='';
	$actionObj->target='_blank';
	$actionObj->type='danger';
}

$res_arr = array('msgdesc'=>$actionObj, 'head_k'=>$head_k, 'sub_k'=>$sub_k, 'part_k'=>$part_k);

echo json_encode($res_arr);
//---