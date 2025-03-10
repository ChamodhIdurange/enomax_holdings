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
$table = 'tbl_damage_return';

// Table's primary key
$primaryKey = 'idtbl_damage_return';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_damage_return`', 'dt' => 'idtbl_damage_return', 'field' => 'idtbl_damage_return' ),
	array( 'db' => '`u`.`returndate`', 'dt' => 'returndate', 'field' => 'returndate' ),
	array( 'db' => '`u`.`qty`', 'dt' => 'qty', 'field' => 'qty' ),
	array( 'db' => '`u`.`comsendstatus`', 'dt' => 'comsendstatus', 'field' => 'comsendstatus' ),
	array( 'db' => '`u`.`comsenddate`',   'dt' => 'comsenddate', 'field' => 'comsenddate' ),
	array( 'db' => '`u`.`backstockstatus`',   'dt' => 'backstockstatus', 'field' => 'backstockstatus' ),
	array( 'db' => '`u`.`backstockdate`',   'dt' => 'backstockdate', 'field' => 'backstockdate' ),
	array( 'db' => '`u`.`returncusstatus`',   'dt' => 'returncusstatus', 'field' => 'returncusstatus' ),
	array( 'db' => '`u`.`returncusdate`',   'dt' => 'returncusdate', 'field' => 'returncusdate' ),
	array( 'db' => '`ua`.`name`',   'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`ub`.`product_name`',   'dt' => 'product_name', 'field' => 'product_name' ),
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

$joinQuery = "FROM `tbl_damage_return` AS `u` LEFT JOIN `tbl_customer` AS `ua` ON (`ua`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`) LEFT JOIN `tbl_product` AS `ub` ON (`ub`.`idtbl_product` = `u`.`tbl_product_idtbl_product`)";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);