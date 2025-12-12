<?php

$table = 'tbl_vehicle_loading';
$primaryKey = 'idtbl_vehicle_loading';

$columns = array(
    array('db' => 'vl.idtbl_vehicle_loading', 'dt' => 'idtbl_vehicle_loading', 'field' => 'idtbl_vehicle_loading'),
    array('db' => 'vl.update_datetime', 'dt' => 'loadingdate', 'field' => 'update_datetime'),
    array('db' => 'v.vehicleno', 'dt' => 'vehicleno', 'field' => 'vehicleno'),
    array('db' => 'u.name', 'dt' => 'name', 'field' => 'name'),
    array('db' => 'vl.status', 'dt' => 'status', 'field' => 'status')
);

// Database connection info
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

// Load custom SSP class
require('ssp.customized.class.php');

$joinQuery = "FROM tbl_vehicle_loading AS vl
              LEFT JOIN tbl_vehicle AS v ON v.idtbl_vehicle = vl.tbl_vehicle_idtbl_vehicle
              LEFT JOIN tbl_user AS u ON u.idtbl_user = vl.tbl_user_idtbl_user";

// Optional WHERE clause
$extraWhere = "vl.status NOT IN (4,5)";


// Output
echo json_encode(
	SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
