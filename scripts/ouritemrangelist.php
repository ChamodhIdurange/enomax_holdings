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
$table = 'tbl_product';

// Table's primary key
$primaryKey = 'idtbl_product';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => '`s`.`update`', 'dt' => 'update', 'field' => 'update'),
    array('db' => '`s`.`product_code`', 'dt' => 'product_code', 'field' => 'product_code'),
    array('db' => '`s`.`product_name`', 'dt' => 'product_name', 'field' => 'product_name'),
    array('db' => '`s`.`saleprice`', 'dt' => 'saleprice', 'field' => 'saleprice'),
    array('db' => '`s`.`retail`', 'dt' => 'retail', 'field' => 'retail'),
    array('db' => '`s`.`unitprice`', 'dt' => 'unitprice', 'field' => 'unitprice'),
    array('db' => '`s`.`total_qty`', 'dt' => 'total_qty', 'field' => 'total_qty'),
    array('db' => '`s`.`last_month_qty`', 'dt' => 'last_month_qty', 'field' => 'last_month_qty'),
    array('db' => '`s`.`current_month_qty`', 'dt' => 'current_month_qty', 'field' => 'current_month_qty'),
    array('db' => '`s`.`percentageVal`', 'dt' => 'percentageVal', 'field' => 'percentageVal'),

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
require('ssp.customized.class.php');

$joinQuery = "FROM (
    SELECT
        u.update,
        ua.product_code,
        ua.product_name,
        ua.saleprice,
        ua.unitprice,
        ua.retail,
        SUM(u.qty) AS total_qty,
        SUM(CASE 
            WHEN MONTH(u.update) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) THEN u.qty 
            ELSE 0 
        END) AS last_month_qty,
        SUM(CASE 
            WHEN MONTH(u.update) = MONTH(CURRENT_DATE()) THEN u.qty 
            ELSE 0 
        END) AS current_month_qty,
        (ua.saleprice - ua.unitprice) * 100 / ua.unitprice AS percentageVal
    FROM
        tbl_stock AS u
    LEFT JOIN
        tbl_product AS ua ON u.tbl_product_idtbl_product = ua.idtbl_product
    GROUP BY 
        ua.idtbl_product
) AS `s`";

// $extraWhere = "WHERE `u`.`status` ='1'";

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery)
);
