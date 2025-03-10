<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_gl_payments';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'rhead.creditor_name', 'dt' => 'payment_creditor', 'field' => 'creditor_name' ),
	array( 'db' => 'rhead.payment_credit_branch_code', 'dt' => 'payment_credit_branch', 'field' => 'payment_credit_branch_code' ),
	array( 'db' => 'racc.subaccountname AS payment_credit_subaccount', 'dt' => 'payment_credit_account', 'field' => 'payment_credit_subaccount' ),
	array( 'db' => 'rhead.payment_head_narration', 'dt' => 'payment_head_narration', 'field' => 'payment_head_narration' ),
	array( 'db' => 'rhead.rinfo.payment_head_amount', 'dt' => 'payment_head_amount', 'field' => 'payment_head_amount' ),
	array( 'db' => 'rhead.id', 'dt' => 'header_id', 'field' => 'id' )
);

// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM tbl_gl_payments AS rhead INNER JOIN (SELECT tbl_gl_payment_id, SUM(paid_amount) AS payment_head_amount FROM tbl_gl_payment_details WHERE payment_cancel=0 GROUP BY tbl_gl_payment_id) AS rinfo ON rhead.id=rinfo.tbl_gl_payment_id INNER JOIN (SELECT subaccount, subaccountname FROM `tbl_subaccount`) AS `racc` ON rhead.payment_credit_subaccount=`racc`.`subaccount`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);