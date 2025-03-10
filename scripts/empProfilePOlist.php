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
$table = 'tbl_porder';

// Table's primary key
$primaryKey = 'idtbl_porder';
$record=$_GET['recordID'];


// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_porder`', 'dt' => 'idtbl_porder', 'field' => 'idtbl_porder' ),
	array( 'db' => '`u`.`orderdate`', 'dt' => 'orderdate', 'field' => 'orderdate' ),
	array( 'db' => '`u`.`subtotal`', 'dt' => 'subtotal', 'field' => 'subtotal' ),
	array( 'db' => '`u`.`disamount`', 'dt' => 'disamount', 'field' => 'disamount' ),
	array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
	array( 'db' => '`u`.`confirmstatus`', 'dt' => 'confirmstatus', 'field' => 'confirmstatus' ),
	array( 'db' => '`u`.`paystatus`',   'dt' => 'paystatus', 'field' => 'paystatus' ),
	array( 'db' => '`u`.`shipstatus`',   'dt' => 'shipstatus', 'field' => 'shipstatus' ),
	array( 'db' => '`u`.`deliverystatus`',   'dt' => 'deliverystatus', 'field' => 'deliverystatus' ),
	array( 'db' => '`u`.`trackingno`',   'dt' => 'trackingno', 'field' => 'trackingno' ),
	array( 'db' => '`u`.`callstatus`',   'dt' => 'callstatus', 'field' => 'callstatus' ),
	array( 'db' => '`u`.`status`',   'dt' => 'status', 'field' => 'status' ),
	array( 'db' => '`ub`.`area`', 'dt' => 'area', 'field' => 'area' ),
    array( 'db' => '`uc`.`name`', 'dt' => 'cusname', 'field' => 'cusname', 'as' => 'cusname' ),
    array( 'db' => '`ud`.`name`', 'dt' => 'repname', 'field' => 'repname', 'as' => 'repname' )
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

$joinQuery = "FROM `tbl_porder` AS `u` LEFT JOIN `tbl_porder_otherinfo` AS `ua` ON (`ua`.`porderid` = `u`.`idtbl_porder`) LEFT JOIN `tbl_area` AS `ub` ON (`ub`.`idtbl_area` = `ua`.`areaid`) LEFT JOIN `tbl_customer` AS `uc` ON (`uc`.`idtbl_customer` = `ua`.`customerid`) LEFT JOIN `tbl_employee` AS `ud` ON (`ud`.`idtbl_employee` = `ua`.`repid`)";

$extraWhere = "`u`.`confirmstatus` IN (1,0,2) AND `u`.`status`=1 AND `u`.`potype`=1 AND `ua`.`repid` = '$record'";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);