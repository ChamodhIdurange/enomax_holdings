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
$table = 'tbl_invoice';

// Table's primary key
$primaryKey = 'idtbl_invoice';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_invoice`', 'dt' => 'idtbl_invoice', 'field' => 'idtbl_invoice' ),
	array( 'db' => '`u`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`u`.`invoiceno`', 'dt' => 'invoiceno', 'field' => 'invoiceno' ),
	array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
	array( 'db' => '`u`.`paymentcomplete`', 'dt' => 'paymentcomplete', 'field' => 'paymentcomplete' ),
	array( 'db' => '`u`.`vat_status`', 'dt' => 'vat_status', 'field' => 'vat_status' ),
	array( 'db' => '`ua`.`name`',   'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`ub`.`idtbl_customer_order`',   'dt' => 'idtbl_customer_order', 'field' => 'idtbl_customer_order' ),
	array( 'db' => '`ub`.`return`',   'dt' => 'return', 'field' => 'return' ),
	array( 'db' => '`ub`.`cuspono`',   'dt' => 'cuspono', 'field' => 'cuspono' ),
	array( 'db' => '`uc`.`name`', 'dt' => 'salepep', 'field' => 'salepep', 'as' => 'salepep' ),
	array( 'db' => '`ud`.`area`',   'dt' => 'area', 'field' => 'area' ),
	array( 'db' => '`u`.`status`',   'dt' => 'status', 'field' => 'status' )
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

$joinQuery = "FROM `tbl_invoice` AS `u` LEFT JOIN `tbl_customer` AS `ua` ON (`ua`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`) LEFT JOIN `tbl_customer_order` AS `ub` ON (`ub`.`idtbl_customer_order` = `u`.`tbl_customer_order_idtbl_customer_order`) LEFT JOIN `tbl_employee` AS `uc` ON (`uc`.`idtbl_employee` = `ub`.`tbl_employee_idtbl_employee`) LEFT JOIN `tbl_area` AS `ud` ON (`ud`.`idtbl_area` = `u`.`tbl_area_idtbl_area`)";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
// JOIN `tbl_porder` AS `p` ON (`p`.`idtbl_porder` = `u`.`asd`)