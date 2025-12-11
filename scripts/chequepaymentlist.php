<?php
error_reporting(0);
ini_set('display_errors', 0);

$table = 'tbl_cheque_payments';
$primaryKey = 'idtbl_cheque_payments';

$statusFilter = isset($_POST['statusFilter']) ? intval($_POST['statusFilter']) : null;

if($statusFilter !== null){
    $extraWhere = "cp.status = $statusFilter";
} else {
    $extraWhere = "cp.status IN (0,1,3)";
}


$columns = array(
    array('db' => 'cp.idtbl_cheque_payments', 'dt' => 'id', 'field' => 'idtbl_cheque_payments'),
    array('db' => 'cp.cheque_number', 'dt' => 'cheque_number', 'field' => 'cheque_number'),
    array('db' => 'b.bankname', 'dt' => 'bank_name', 'field' => 'bankname'),
    array('db' => 'br.branchname', 'dt' => 'branch_name', 'field' => 'branchname'),
    array('db' => 'cp.amount', 'dt' => 'amount', 'field' => 'amount'),
    array('db' => 'cp.payment_date', 'dt' => 'payment_date', 'field' => 'payment_date'),
    array('db' => 'c.customer', 'dt' => 'customer', 'field' => 'customer'),
    array('db' => 'c.idtbl_customer', 'dt' => 'customer_id', 'field' => 'idtbl_customer'),
    array('db' => 'cp.status', 'dt' => 'status', 'field' => 'status'),
    array('db' => 'cp.is_payment_added', 'dt' => 'is_payment_added', 'field' => 'is_payment_added')
);

require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "FROM tbl_cheque_payments AS cp
              LEFT JOIN tbl_bank AS b ON cp.tbl_bank_idtbl_bank = b.idtbl_bank
              LEFT JOIN tbl_bank_branch AS br ON cp.tbl_bank_branch_idtbl_bank_branch = br.idtbl_bank_branch
              LEFT JOIN tbl_customer c ON cp.tbl_customer_idtbl_customer = c.idtbl_customer";
              

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
exit;
