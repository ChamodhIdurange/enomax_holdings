<?php
require_once('../connection/db.php');

$request = $_POST;

// DataTables parameters
$start = isset($request['start']) ? intval($request['start']) : 0;
$length = isset($request['length']) ? intval($request['length']) : 10;
$searchValue = $request['search']['value'] ?? '';

// Base query: only completed vehicle loadings
$sql = "SELECT 
            vl.idtbl_vehicle_loading, 
            v.vehicleno, 
            u.name AS drivername, 
            vl.update_datetime AS loadingdate,
            vl.total_items,
            vl.status,
            COALESCE(SUM(vld.qty_remaining), 0) AS total_remaining
        FROM tbl_vehicle_loading vl
        INNER JOIN tbl_vehicle v ON vl.tbl_vehicle_idtbl_vehicle = v.idtbl_vehicle
        INNER JOIN tbl_user u ON vl.tbl_user_idtbl_user = u.idtbl_user
        LEFT JOIN tbl_vehicle_loading_details vld ON vl.idtbl_vehicle_loading = vld.tbl_vehicle_loading_idtbl_vehicle_loading
        WHERE vl.status IN (3,5)"; 

// Add search filter
if (!empty($searchValue)) {
    $sql .= " AND (v.vehicleno LIKE '%$searchValue%' OR u.name LIKE '%$searchValue%')";
}

$sql .= " GROUP BY vl.idtbl_vehicle_loading ORDER BY vl.idtbl_vehicle_loading DESC";

// Paging
$sql .= " LIMIT $start, $length";

$data = [];
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Total records
$totalRecordsQuery = "SELECT COUNT(*) AS cnt FROM tbl_vehicle_loading WHERE status = 3";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['cnt'];

echo json_encode([
    "draw" => intval($request['draw'] ?? 1),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data
]);
