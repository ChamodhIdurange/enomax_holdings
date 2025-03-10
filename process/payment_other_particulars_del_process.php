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

    echo json_encode(array('msgdesc'=>$actionObj));
	
	die();
}

require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$updatedatetime=date('Y-m-d h:i:s');


$head_k = ''; /*invoice-id*/
$sub_k = '';
$part_k = '';

$conn->autocommit(FALSE);
$flag = true;

if(isset($_POST['ref_id'])){
	$head_k = $_POST['ref_id'];
}
/**/
if(isset($_POST['sub_id'])){
	$sub_k = $_POST['sub_id'];
}

if(isset($_POST['part_id'])){
	$part_k = $_POST['part_id'];
}

$presql = "SELECT id FROM tbl_gl_payments WHERE id=? AND payment_complete=0";
$stmtFinalize = $conn->prepare($presql);
$stmtFinalize->bind_param('s', $head_k);
$stmtFinalize->execute();
$stmtFinalize->store_result();

if($stmtFinalize->num_rows==0){
	$flag=false;
}

if($flag){
	$updateSQL = "UPDATE tbl_gl_payment_details SET paid_amount=paid_amount-?, updated_by=?, updated_at=NOW() WHERE id=? AND payment_cancel=0";
	
	$stmt = $conn->prepare($updateSQL);
	$stmt->bind_param("sss", $_POST['tot_paid'], $userID, $sub_k);
	$ResultOut = $stmt->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
	
	$stmt->close();
	
	$updateSQL = "UPDATE tbl_gl_payment_detail_particulars SET payment_part_cancel=1, updated_by=?, updated_at=NOW() WHERE id=? AND payment_part_cancel=0";
	
	$stmtPart = $conn->prepare($updateSQL);
	$stmtPart->bind_param("ss", $userID, $part_k);
	$ResultOut = $stmtPart->execute();
	
	$affectedRowCnt = $conn->affected_rows;
	
	if(!($affectedRowCnt==1)){
		$flag = false;
	}
}

$actionObj=new stdClass();

if ($flag) {
	$conn->commit();
	/*
	echo "All queries were executed successfully";
	*/
	$actionObj->icon='fas fa-check-circle';
	$actionObj->title='';
	$actionObj->message='Deleted Successfully';
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