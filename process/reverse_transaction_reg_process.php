<?php 
session_start();

$field_error_msg='';

if(!isset($_SESSION['userid'])){
	$field_error_msg='Session Expired';
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

$logCancel=$_POST['detail_cancel'];

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/
$sub_k = '';

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}
/**/
if(isset($_POST['sub_id'])){
	$sub_k = $_POST['sub_id'];
}



$receiptRefNo=$_POST['receipt_refno'];
$transactionRefNo=$_POST['trn_num'];


$conn->autocommit(FALSE);
$flag = true;
$resmsg = '';

/*
check-if-account-type-is-pettycash
*/
/**/
$pre_sql="SELECT tbl_account_transaction.acccode FROM tbl_account_transaction INNER JOIN tbl_subaccount ON tbl_account_transaction.acccode=tbl_subaccount.subaccount WHERE tbl_account_transaction.trano=? AND tbl_subaccount.tbl_account_category_idtbl_account_category=3";
$pre_stmt=$conn->prepare($pre_sql);
$pre_stmt->bind_param('s', $transactionRefNo);
$pre_stmt->execute();
$pre_stmt->store_result();
$pre_stmt->bind_result($acccode);

$pre_stmt_cnt = $pre_stmt->num_rows;


/*
check-invoice-no-collisions-among-different-tables
*/
/*
$pre_sql="SELECT drv_rev.accamount FROM (SELECT receiptinvno, paytype, accamount FROM tbl_account_transaction WHERE trano=? AND seqno>0 AND receiptinvno<>0) AS drv_rev ";
*/
$pre_sql="SELECT drv_rev.accamount FROM (SELECT receiptinvno, paytype, accamount FROM tbl_account_transaction WHERE trano=? AND receiptinvno<>0 AND tratype='P' GROUP BY receiptinvno) AS drv_rev ";

$pre_sql.="LEFT OUTER JOIN (SELECT idtbl_invoice AS receiptinvno, total AS accamount FROM tbl_invoice WHERE addtoaccountstatus=1) AS drv_crinfo ON (drv_rev.receiptinvno=drv_crinfo.receiptinvno AND drv_rev.paytype='CR') LEFT OUTER JOIN (SELECT receiptinvno, SUM(accamount) AS accamount FROM (SELECT idtbl_invoice_payment_detail AS receiptinvno, amount AS accamount FROM tbl_invoice_payment_detail WHERE addaccountstatus=1 UNION ALL SELECT tbl_gl_payment_details.payment_invoice_no AS receiptinvno, tbl_gl_payment_details.paid_amount AS accamount FROM tbl_gl_payment_details INNER JOIN (SELECT id FROM tbl_gl_payments WHERE payment_complete=1) AS tbl_gl_payments ON tbl_gl_payment_details.tbl_gl_payment_id=tbl_gl_payments.id WHERE tbl_gl_payment_details.payment_cancel=0 AND tbl_gl_payment_details.payment_invoice_no<>0) AS drv_pay GROUP BY receiptinvno) AS drv_cxinfo ON (drv_rev.receiptinvno=drv_cxinfo.receiptinvno AND drv_rev.paytype IN ('CA', 'CH')) WHERE (drv_rev.accamount-(IFNULL(drv_crinfo.accamount, 0)+IFNULL(drv_cxinfo.accamount, 0)))<>0";
$inv_stmt=$conn->prepare($pre_sql);
$inv_stmt->bind_param('s', $transactionRefNo);
$inv_stmt->execute();
$inv_stmt->store_result();
$inv_stmt->bind_result($accamount);

$inv_stmt_cnt = $inv_stmt->num_rows;

$flag=(($pre_stmt_cnt+$inv_stmt_cnt)==0)?$flag:false;

if($flag){
	if($head_k==''){
		$updateSQL = "INSERT INTO tbl_gl_rev_audits (created_by, created_at) VALUES (?, NOW())";
	
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("s", $userID);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt==1){
			$head_k = $stmt->insert_id;
			
		}else{
			$flag = false;
		}
		
		$stmt->close();
	}
	
	if($sub_k==''){
		$updateSQL = "INSERT INTO tbl_gl_rev_audit_details (tbl_gl_rev_audit_id, idtbl_account_transaction, trano, created_by, created_at) VALUES (?, ?, ?, ?, NOW())";
	
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("ssss", $head_k, $receiptRefNo, $transactionRefNo, $userID);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		if($affectedRowCnt==1){
			$sub_k = $stmt->insert_id;
			$resmsg = '<h5>Receipt added successfully</h5>';
		}else{
			$flag = false;
		}
		
		$stmt->close();
		
	}else{
		$updateSQL = "UPDATE tbl_gl_rev_audit_details SET log_cancel=?, updated_by=?, updated_at=NOW() WHERE id=?";
		
		$stmt = $conn->prepare($updateSQL);
		$stmt->bind_param("sss", $logCancel, $userID, $sub_k);
		$ResultOut = $stmt->execute();
		
		$affectedRowCnt = $conn->affected_rows;
		
		$resmsg = ($logCancel==0)?'<h5>Receipt added successfully</h5>':'<h5>Receipt removed successfully</h5>';
		
		if(!($affectedRowCnt==1)){
			$flag = false;
		}
		
		$stmt->close();
	}
	
	/**/
}

$actionObj=new stdClass();

if ($flag) {
	$conn->commit();
	/*
	echo "All queries were executed successfully";
	*/
	$actionObj->icon='fas fa-check-circle';
	$actionObj->title='';
	$actionObj->message=$resmsg;//'Add Successfully';
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

$res_arr = array('msgdesc'=>$actionObj, 'head_k'=>$head_k, 'sub_k'=>$sub_k);

echo json_encode($res_arr);
//---