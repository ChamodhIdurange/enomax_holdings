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
$table = 'tbl_customer';

// Table's primary key
$primaryKey = 'idtbl_customer';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_customer`', 'dt' => 'idtbl_customer', 'field' => 'idtbl_customer' ),
	array( 'db' => '`u`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`u`.`nic`', 'dt' => 'nic', 'field' => 'nic' ),
	array( 'db' => '`u`.`phone`', 'dt' => 'phone', 'field' => 'phone' ),
	array( 'db' => '`u`.`status`',   'dt' => 'status', 'field' => 'status' ),
	array( 'db' => '`u`.`type`',   'dt' => 'type', 'field' => 'type' ),
	array( 'db' => '`u`.`address`',   'dt' => 'address', 'field' => 'address' ),
	array( 'db' => '`e`.`name`', 'dt' => 'empname', 'field' => 'empname', 'as' => 'empname' ),
	array( 'db' => '`ud`.`area`',   'dt' => 'area', 'field' => 'area' )
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

$joinQuery = "FROM `tbl_customer` AS `u` LEFT JOIN `tbl_area` AS `ud` ON (`ud`.`idtbl_area` = `u`.`tbl_area_idtbl_area`) LEFT JOIN `tbl_employee` AS `e` ON (`e`.`idtbl_employee` = `u`.`ref`)";

$extraWhere = "`u`.`status` IN (1, 2, 5)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);