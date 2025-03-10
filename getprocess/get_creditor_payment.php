<?php
require_once('../connection/db.php');

$receiptID=$_POST['refID'];
$payment_complete=0;

//header
$query_rsReceipt = "select creditor_name, payment_head_narration, payment_credit_branch, payment_credit_account, payment_complete from tbl_gl_payments WHERE id=?";

$stmtReceipt = $conn->prepare($query_rsReceipt);
$stmtReceipt->bind_param('s', $receiptID);
$stmtReceipt->execute();
$stmtReceipt->store_result();
$totalRows_rsReceipt = $stmtReceipt->num_rows;
$stmtReceipt->bind_result($creditor_name, $payment_head_narration, $payment_credit_branch, $payment_credit_account, $payment_complete);

if($totalRows_rsReceipt==1){
	$row_rsReceipt = $stmtReceipt->fetch();
}

//detail
$query_rsDetails = "select tbl_gl_payment_details.id as detail_id, tbl_gl_payment_details.payment_debit_branch AS payment_debit_branch_id, tbl_gl_payment_details.payment_debit_branch_code AS payment_debit_branch, tbl_gl_payment_details.payment_debit_account AS debit_acc_id, CONCAT(tbl_gl_payment_details.payment_debit_subaccount, ' ', drv_acc.subaccountname) AS payment_debit_account, tbl_gl_payment_details.payment_sub_narration, tbl_gl_payment_details.payment_invoice_no, tbl_gl_payment_details.settle_by_cash, tbl_gl_payment_details.settle_by_cheque, tbl_gl_payment_details.paid_amount from tbl_gl_payment_details INNER JOIN (SELECT subaccount, subaccountname FROM tbl_subaccount) AS drv_acc ON tbl_gl_payment_details.payment_debit_subaccount=drv_acc.subaccount WHERE tbl_gl_payment_details.tbl_gl_payment_id=? AND tbl_gl_payment_details.payment_cancel=0";

$stmtDetails = $conn->prepare($query_rsDetails);
$stmtDetails->bind_param('s', $receiptID);
$stmtDetails->execute();
$stmtDetails->store_result();
$totalRows_rsDetails = $stmtDetails->num_rows;
$stmtDetails->bind_result($detail_id, $payment_debit_branch_id, $payment_debit_branch, $debit_acc_id, $payment_debit_account, $payment_sub_narration, $payment_invoice_no, $settle_by_cash, $settle_by_cheque, $paid_amount);

$row_rsDetails = $stmtDetails->fetch();

$setCash=1;
$setCheque=0;

$book_data=array();

if($totalRows_rsReceipt==1){
	$setCash=$settle_by_cash;
	$setCheque=$settle_by_cheque;
	
	do{
		$book_data[] = array('detail_id'=>$detail_id, 'debit_branch_id'=>$payment_debit_branch_id, 
							 'payment_debit_branch'=>$payment_debit_branch, 'debit_acc_id'=>$debit_acc_id, 
						'payment_debit_account'=>$payment_debit_account, 'payment_sub_narration'=>$payment_sub_narration, 
						'invoice_no'=>$payment_invoice_no, 'paid_amount'=>$paid_amount);
		
	}while($stmtDetails->fetch());
}


$query_opDetails = "select id as op_detail_id, payment_particulars, paid_amount AS op_paid_amount from tbl_gl_payment_detail_particulars WHERE tbl_gl_payment_id=? AND payment_part_cancel=0";

$stmtParticulars = $conn->prepare($query_opDetails);
$stmtParticulars->bind_param('s', $receiptID);
$stmtParticulars->execute();
$stmtParticulars->store_result();
$totalRows_opDetails = $stmtParticulars->num_rows;
$stmtParticulars->bind_result($op_detail_id, $payment_particulars, $op_paid_amount);

$row_opDetails = $stmtParticulars->fetch();

$particulars_data=array();

if($totalRows_opDetails>0){
	do{
		$particulars_data[] = array('detail_id'=>$op_detail_id, 'paid_particulars'=>$payment_particulars, 'paid_amount'=>$op_paid_amount);
		
	}while($stmtParticulars->fetch());
}

$output = array('pay_creditor'=>$creditor_name, 'payment_head_narration'=>$payment_head_narration, 
				'credit_branch_id'=>$payment_credit_branch, 'credit_acc_id'=>$payment_credit_account, 
				'pay_complete'=>$payment_complete, 
				'set_cash'=>$setCash, 'set_cheque'=>$setCheque, 
				'table_data'=>$book_data, 'particulars_data'=>$particulars_data);

echo json_encode($output);
?>