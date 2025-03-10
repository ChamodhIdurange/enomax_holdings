<?php 
session_start();

if(!isset($_SESSION['userid'])){
	//header ("Location:index.php");
	$actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Session Expired';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
	
	die();
}

require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];
$receipt_complete=0;

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}


$conn->autocommit(FALSE);
$flag = true;

if($head_k!=''){
	$pre_sql = "SELECT payment_credit_subaccount FROM tbl_gl_payments WHERE id=?";
	$stmtAcc = $conn->prepare($pre_sql);
	$stmtAcc->bind_param('s', $head_k);
	$stmtAcc->execute();
	$stmtAcc->store_result();
	$stmtAcc->bind_result($payment_credit_subaccount);
	$stmtAcc->fetch();
	/*
	SELECT drv_r.bank_id, MAX(drv_r.endno) AS max_cn, COALESCE(MAX(drv_p.cheque_no)+1, MIN(drv_r.startno)) AS cur_cn FROM (
SELECT tbl_bank_account_allocation.tbl_bank_idtbl_bank AS bank_id, tbl_cheque_info.startno, tbl_cheque_info.endno FROM tbl_bank_account_allocation INNER JOIN `tbl_cheque_info` ON tbl_bank_account_allocation.idtbl_bank_account_allocation=tbl_cheque_info.tbl_bank_account_allocation_idtbl_bank_account_allocation WHERE tbl_bank_account_allocation.accountno='ASCA00020001' AND tbl_cheque_info.status=1
) AS drv_r INNER JOIN (
SELECT tbl_gl_payment_details.cheque_no FROM (SELECT id FROM tbl_gl_payments WHERE payment_credit_subaccount='ASCA00020001' AND payment_complete=1) AS tbl_gl_payments INNER JOIN tbl_gl_payment_details ON tbl_gl_payments.id=tbl_gl_payment_details.tbl_gl_payment_id
) AS drv_p
	
	bank_id for tbl_gl_payment_details.cheque_bank
	cur_cn - available first cheque no
	max_cn >= available first cheque no + num of cheques to be printed
	
	Assign max_cn=-1, cur_cn=0 WHERE Result for a Sub-Account-Code IS NULL in order to 
	prevent cheque number allocation, hence payment complete process is unable to continue without the required 
	cheque info related debit details as there should be atleast payment-num-rows>=2 for credit and 
	debit records inserted to tbl-account-transaction.
	*/
	$pre_sql = "SELECT IFNULL(drv_r.bank_id, -1) AS bank_id, IFNULL(MAX(drv_r.endno), -1) AS max_cn, COALESCE(MAX(drv_p.cheque_no)+1, MIN(drv_r.startno), 0) AS cur_cn FROM (SELECT tbl_bank_account_allocation.tbl_bank_idtbl_bank AS bank_id, tbl_cheque_info.startno, tbl_cheque_info.endno FROM tbl_bank_account_allocation ";
	$pre_sql .= "INNER JOIN `tbl_cheque_info` ON tbl_bank_account_allocation.idtbl_bank_account_allocation=tbl_cheque_info.tbl_bank_account_allocation_idtbl_bank_account_allocation WHERE tbl_bank_account_allocation.accountno=? AND tbl_cheque_info.status=1) AS drv_r INNER JOIN (SELECT tbl_gl_payment_details.cheque_no FROM (SELECT id FROM tbl_gl_payments WHERE payment_credit_subaccount=? AND payment_complete=1) AS tbl_gl_payments INNER JOIN tbl_gl_payment_details ON tbl_gl_payments.id=tbl_gl_payment_details.tbl_gl_payment_id) AS drv_p";
	$stmtRev = $conn->prepare($pre_sql);
	$stmtRev->bind_param('ss', $payment_credit_subaccount, $payment_credit_subaccount);
	$stmtRev->execute();
	$stmtRev->store_result();
	$stmtRev->bind_result($bank_id, $max_cn, $cur_cn);
	$stmtRev->fetch();//
	
	$pre_sql = "SELECT id AS alloc_id FROM tbl_gl_payment_details WHERE tbl_gl_payment_id=? AND settle_by_cheque=1 AND payment_cancel=0 ORDER BY id DESC";
	$stmtReg = $conn->prepare($pre_sql);
	$stmtReg->bind_param('s', $head_k);
	$stmtReg->execute();
	$stmtReg->store_result();
	$res_cnt = $stmtReg->num_rows;
	$tot_reg = $res_cnt-1;
	$stmtReg->bind_result($alloc_id);
	$stmtReg->fetch();
	
	if($res_cnt>0){
		if($max_cn>=($cur_cn+$tot_reg)){
			do{
				$cheque_no = $cur_cn+$tot_reg;
				$updateSQL = "UPDATE tbl_gl_payment_details SET cheque_no=SUBSTRING(CONCAT('00000', ?), -6, 6), cheque_bank=?, updated_by=?, updated_at=NOW() WHERE id=?";
				$stmt = $conn->prepare($updateSQL);
				$stmt->bind_param('ssss', $cheque_no, $bank_id, $userID, $alloc_id);
				
				$ResultOut = $stmt->execute();
				$affectedRowCnt = $conn->affected_rows;
				
				if($affectedRowCnt==1){
					$tot_reg--;
				}else{
					$flag = false;
				}
			}while($stmtReg->fetch());
		}
	}
	
	$updateSQL = "UPDATE tbl_gl_payments SET payment_complete=1, updated_by=?, updated_at=NOW() WHERE id=? AND payment_complete=0";

	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ss", $userID, $head_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if($affectedRowCnt==1){
		//$receipt_complete=1;
	}else{
		$flag = false;
	}
	
	$stmt->close();
	
	
	/*
	update-petty-cash
	*/
	$pre_sql="SELECT DISTINCT tbl_gl_payment_details.payment_debit_subaccount FROM tbl_gl_payment_details INNER JOIN tbl_subaccount ON tbl_gl_payment_details.payment_debit_subaccount=tbl_subaccount.subaccount WHERE tbl_gl_payment_details.tbl_gl_payment_id=? AND tbl_gl_payment_details.payment_cancel=0 AND tbl_subaccount.tbl_account_category_idtbl_account_category=3";
	$stmtReg = $conn->prepare($pre_sql);
	$stmtReg->bind_param('s', $head_k);
	$stmtReg->execute();
	$stmtReg->store_result();
	$reg_cnt = $stmtReg->num_rows;
	
	if($reg_cnt>0){
		$insertSQL = "INSERT INTO tbl_pettycash_reimburse (`date`, openbal, reimursebal, closebal, accountno, chequeno, chequedate, printstatus, status, insertdatetime, tbl_user_idtbl_user, tbl_subaccount_idtbl_subaccount, tbl_company_idtbl_company, tbl_company_branch_idtbl_company_branch) SELECT DATE(NOW()) AS `date`, IFNULL(drv_acc.closebal, 0) AS openbal, 0 AS reimursebal, (IFNULL(drv_acc.closebal, 0)+drv_reg.add_amt) AS closebal, drv_reg.payment_debit_subaccount As accountno, IFNULL(drv_reg.cheque_no, '') AS chequeno, IFNULL(drv_reg.cheque_date, DATE(NOW())) AS chequedate, 1 AS printstatus, 1 AS status, NOW() AS insertdatetime, ? AS tbl_user_idtbl_user, tbl_subaccount.idtbl_subaccount, tbl_company_branch.tbl_company_idtbl_company, drv_reg.payment_debit_branch AS tbl_company_branch_idtbl_company_branch FROM (SELECT payment_debit_subaccount, payment_debit_branch, cheque_no, cheque_date, SUM(paid_amount) AS add_amt FROM tbl_gl_payment_details WHERE tbl_gl_payment_id=? AND payment_cancel=0 GROUP BY payment_debit_subaccount) AS drv_reg INNER JOIN tbl_subaccount ON drv_reg.payment_debit_subaccount=tbl_subaccount.subaccount INNER JOIN tbl_company_branch ON drv_reg.payment_debit_branch=tbl_company_branch.idtbl_company_branch LEFT OUTER JOIN (SELECT accountno, closebal, tbl_subaccount_idtbl_subaccount AS idtbl_subaccount FROM tbl_pettycash_reimburse WHERE idtbl_pettycash_reimburse IN (SELECT MAX(idtbl_pettycash_reimburse) AS log_id FROM tbl_pettycash_reimburse WHERE status=1 GROUP BY accountno)) AS drv_acc ON drv_reg.payment_debit_subaccount=drv_acc.accountno WHERE tbl_subaccount.tbl_account_category_idtbl_account_category=3";
		$stmt = $conn->prepare($insertSQL);
		$stmt->bind_param("ss", $userID, $head_k);
		$ResultOut = $stmt->execute();
		
		if(!($reg_cnt==$conn->affected_rows)){
			$flag = false;
		}
	}


	/*
	$updateSQL = "INSERT INTO tbl_account_transaction (tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr,  narration, totamount, tradate, paytype, refid, status, refno, updatedatetime, ismatched, branchcode, companycode, tbl_user_idtbl_user) ";
	*/
	$updateSQL = "select drv_year.tbl_master_idtbl_master, drv_main.trano_refno AS trano, 'P' AS traid, (@row_number:=@row_number+1)-1 AS seqno, drv_main.acccode, IFNULL(accamount, (drv_sums.cash_amount*drv_main.cash_type)+(drv_sums.cheque_amount*(1-drv_main.cash_type))) AS accamount, drv_main.crdr, drv_main.narration, drv_sums.totamount, drv_main.tradate, IFNULL(drv_paytype.cols_text, 'CH') AS paytype, drv_main.receiptinvno, 0 AS refid, 0 as status, drv_main.trano_refno AS refno, IFNULL(drv_main.cheque_no, '-1') AS cheque_no, IFNULL(drv_main.doc_detail_id, -1) AS doc_detail_id, drv_main.systemdate as updatedatetime, 0 as ismatched, tbl_company_branch.code as branchcode, tbl_company.code AS companycode, ? AS tbl_user_idtbl_user from (";
	
	$updateSQL .= "select drv_h.trano, drv_h.trano_refno, NULL AS cheque_no, NULL AS doc_detail_id, drv_h.traid,  drv_h.branch_id, drv_h.acccode, NULL AS accamount, drv_h.crdr, drv_h.narration, drv_h.tradate, drv_r.cash_type, '0' AS receiptinvno, drv_h.systemdate FROM (";
	
	$updateSQL .= "select id as trano, CONCAT('P', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano_refno, 'P' AS traid, payment_credit_branch AS branch_id, payment_credit_subaccount AS acccode, 'C' AS crdr, payment_head_narration AS narration, DATE(created_at) AS tradate, DATE(NOW()) AS systemdate from tbl_gl_payments where id=?) AS drv_h CROSS JOIN (select 1 AS cash_type UNION ALL select 0 As cash_type) AS drv_r UNION ALL ";
	
	$updateSQL .= "select tbl_gl_payment_id as trano, CONCAT('P', SUBSTRING(CONCAT('00000000', tbl_gl_payment_id), -9, 9)) AS trano_refno, cheque_no, id AS doc_detail_id, 'P' AS traid, payment_debit_branch AS branch_id, payment_debit_subaccount AS acccode, paid_amount AS accamount, 'D' AS crdr, payment_sub_narration AS narration, DATE(created_at) AS tradate, settle_by_cash AS cash_type, payment_invoice_no AS receiptinvno, DATE(NOW()) AS systemdate from tbl_gl_payment_details WHERE tbl_gl_payment_id=? and payment_cancel=0 AND ((settle_by_cash=1) OR (settle_by_cheque=1 AND cheque_no IS NOT NULL))) AS drv_main INNER JOIN (";
	
	$updateSQL .= "select tbl_gl_payment_id AS trano, SUM(settle_by_cash * paid_amount) AS cash_amount, SUM(settle_by_cheque * paid_amount) AS cheque_amount, SUM(paid_amount) AS totamount from tbl_gl_payment_details WHERE tbl_gl_payment_id=? and payment_cancel=0 GROUP BY trano) AS drv_sums ON drv_main.trano=drv_sums.trano INNER JOIN (";
	
	$updateSQL .= "select idtbl_master AS tbl_master_idtbl_master, tbl_company_branch_idtbl_company_branch AS branch_id from tbl_master where status=1) AS drv_year ON drv_main.branch_id=drv_year.branch_id INNER JOIN ";
	
	$updateSQL .= "tbl_company_branch ON drv_year.branch_id=tbl_company_branch.idtbl_company_branch INNER JOIN ";
	
	$updateSQL .= "tbl_company ON tbl_company_branch.tbl_company_idtbl_company=tbl_company.idtbl_company LEFT OUTER JOIN (";
	
	$updateSQL .= "select 1 AS rows_type, 'CA' AS cols_text) AS drv_paytype ON drv_main.cash_type=drv_paytype.rows_type CROSS JOIN (";
	
	$updateSQL .= "select @row_number:=0) AS t HAVING accamount>0";
	
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("ssss", $userID, $head_k, $head_k, $head_k);
	
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($tbl_master_idtbl_master, $trano, $traid, $seqno, $acccode, $accamount, $crdr, $narration, $totamount, $tradate, $paytype, $receiptinvno, $refid, $status, $refno, $cheque_no, $doc_detail_id, $updatedatetime, $ismatched, $branchcode, $companycode, $tbl_user_idtbl_user);
	/*
	
	*/
	if($stmt->num_rows>=2){
		$stmt->fetch();
		
		do{
			$insertSQL = "INSERT INTO tbl_account_transaction (tbl_master_idtbl_master, trano, tratype, seqno, acccode, accamount, crdr, narration, totamount, tradate, paytype, receiptinvno, refid, status, refno, updatedatetime, ismatched, branchcode, companycode, tbl_user_idtbl_user) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmtInsert = $conn->prepare($insertSQL);
			$stmtInsert->bind_param('ssssssssssssssssssss', $tbl_master_idtbl_master, $trano, $traid, $seqno, $acccode, $accamount, $crdr, $narration, $totamount, $tradate, $paytype, $receiptinvno, $refid, $status, $refno, $updatedatetime, $ismatched, $branchcode, $companycode, $tbl_user_idtbl_user);
			
			$ResultOut = $stmtInsert->execute();
			$affectedRowCnt = $conn->affected_rows;
			$idtbl_account_transaction = $stmtInsert->insert_id;
			
			if(($paytype=='CH') && ($doc_detail_id!='-1')){
				$insertSQL = "INSERT INTO tbl_gl_account_transaction_details (idtbl_account_transaction, tratype, paytype, crdr, cheque_no, doc_detail_id) VALUES (?, ?, ?, ?, ?, ?)";
				
				$affectedRowCnt = 0;
				
				$stmtDoc = $conn->prepare($insertSQL);
				$stmtDoc->bind_param('ssssss', $idtbl_account_transaction, $traid, $paytype, $crdr, $cheque_no, $doc_detail_id);
				$ResultOut = $stmtDoc->execute();
				$affectedRowCnt = $conn->affected_rows;
			}
			
			/*
			if($affectedRowCnt>=2){
			*/
			if($affectedRowCnt==1){
				//$receipt_complete = 1;
			}else{
				$flag = false;
			}
			
		}while($stmt->fetch());
	}else{
		$flag = false;
	}
}

$actionObj=new stdClass();

if ($flag) {
	$conn->commit();
	$receipt_complete = 1;
	/*
	echo "All queries were executed successfully";
	*/
	$actionObj->icon='fas fa-check-circle';
	$actionObj->title='';
	$actionObj->message='Process Completed Successfully';
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

$res_arr = array('msgdesc'=>$actionObj, 'rec_complete'=>$receipt_complete);

echo json_encode($res_arr);
//---