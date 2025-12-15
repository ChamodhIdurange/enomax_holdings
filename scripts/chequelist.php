<?php

$table = 'tbl_cheque_info';
$primaryKey = 'idtbl_cheque_info';

$columns = array(
    array('db' => 'c.idtbl_cheque_info', 'dt' => 'idtbl_cheque_info', 'field' => 'idtbl_cheque_info'),
    array('db' => 'c.startno', 'dt' => 'startno', 'field' => 'startno'),
    array('db' => 'c.endno', 'dt' => 'endno', 'field' => 'endno'),
    array('db' => 'b.bankname AS bankname', 'dt' => 'bankname', 'field' => 'bankname'),
    array('db' => 'br.branchname AS branchname', 'dt' => 'branchname', 'field' => 'branchname'),
    array('db' => 'a.accountno AS accountno', 'dt' => 'accountno', 'field' => 'accountno'),
    array('db' => 'u.username AS updateuser', 'dt' => 'updateuser', 'field' => 'updateuser'),
    array('db' => 'c.status', 'dt' => 'status', 'field' => 'status')
);

require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "FROM tbl_cheque_info AS c
              LEFT JOIN tbl_bank AS b ON b.idtbl_bank = c.tbl_bank_idtbl_bank
              LEFT JOIN tbl_bank_branch AS br ON br.idtbl_bank_branch = c.tbl_bank_branch_idtbl_bank_branch
              LEFT JOIN tbl_account AS a ON a.idtbl_account = c.tbl_account_idtbl_account
              LEFT JOIN tbl_user AS u ON u.idtbl_user = c.tbl_user_idtbl_user";

$extraWhere = "c.status IN (0, 1, 2)";

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
