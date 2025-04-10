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
$table = 'tbl_customer_order';

// Table's primary key
$primaryKey = 'idtbl_customer_order';



$fromdate = isset($_POST['fromdate']) ? $_POST['fromdate'] : '';
$todate = isset($_POST['todate']) ? $_POST['todate'] : '';


$columns = array(
	array( 'db' => '`u`.`idtbl_customer_order`', 'dt' => 'idtbl_customer_order', 'field' => 'idtbl_customer_order' ),
	array( 'db' => '`u`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`u`.`total`', 'dt' => 'total', 'field' => 'total' ),
	array( 'db' => '`u`.`discount`', 'dt' => 'discount', 'field' => 'discount' ),
	array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
	array( 'db' => '`u`.`confirm`', 'dt' => 'confirm', 'field' => 'confirm' ),
	array( 'db' => '`u`.`dispatchissue`', 'dt' => 'dispatchissue', 'field' => 'dispatchissue' ),
	array( 'db' => '`u`.`ship`',   'dt' => 'ship', 'field' => 'ship' ),
	array( 'db' => '`u`.`delivered`',   'dt' => 'delivered', 'field' => 'delivered' ),
	array( 'db' => '`u`.`is_printed`',   'dt' => 'is_printed', 'field' => 'is_printed' ),
	array( 'db' => '`u`.`status`',   'dt' => 'status', 'field' => 'status' ),
	array( 'db' => '`u`.`remark`',   'dt' => 'remark', 'field' => 'remark' ),
	array( 'db' => '`ub`.`area`', 'dt' => 'area', 'field' => 'area' ),
    array( 'db' => '`uc`.`name`', 'dt' => 'cusname', 'field' => 'cusname', 'as' => 'cusname' ),
	array( 'db' => '`uc`.`address`', 'dt' => 'cusaddress', 'field' => 'cusaddress', 'as' => 'cusaddress' ),
	array( 'db' => '`u`.`tbl_customer_idtbl_customer`',   'dt' => 'tbl_customer_idtbl_customer', 'field' => 'tbl_customer_idtbl_customer' ),
	array( 'db' => '`u`.`tbl_employee_idtbl_employee`',   'dt' => 'tbl_employee_idtbl_employee', 'field' => 'tbl_employee_idtbl_employee' ),
    array( 'db' => '`ud`.`name`', 'dt' => 'repname', 'field' => 'repname', 'as' => 'repname' ),
	array( 'db' => '`u`.`cuspono`',   'dt' => 'cuspono', 'field' => 'cuspono' ),
	array( 'db' => '`od`.`deliverRemarks`',   'dt' => 'deliverRemarks', 'field' => 'deliverRemarks' ),
	array( 'db' => '`od`.`deliverDate`',   'dt' => 'deliverDate', 'field' => 'deliverDate' ),
	array( 'db' => '`od`.`tbl_vehicle_idtbl_vehicle`',   'dt' => 'tbl_vehicle_idtbl_vehicle', 'field' => 'tbl_vehicle_idtbl_vehicle' ),
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

$joinQuery = "FROM `tbl_customer_order` AS `u` LEFT JOIN `tbl_area` AS `ub` ON (`ub`.`idtbl_area` = `u`.`tbl_area_idtbl_area`) LEFT JOIN `tbl_customer` AS `uc` ON (`uc`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`) LEFT JOIN `tbl_employee` AS `ud` ON (`ud`.`idtbl_employee` = `u`.`tbl_employee_idtbl_employee`) LEFT JOIN `tbl_customer_order_delivery_data` AS `od` ON (`od`.`tbl_customer_order_idtbl_customer_order` = `u`.`idtbl_customer_order`)";

$extraWhere = "`u`.`status` = 1 AND `u`.`confirm` = 1 AND `u`.`dispatchissue` = 1 AND  `u`.`delivered` = 1";

if (!empty($fromdate)) {
    $extraWhere .= " AND `u`.`date` BETWEEN '$fromdate' AND '$todate'";
}

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);