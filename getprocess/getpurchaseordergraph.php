<?php
require_once('../connection/db.php');

// Optional filters (date range if you need)
$validfrom = $_POST['validfrom'] ?? null;
$validto   = $_POST['validto'] ?? null;

$where = "1=1";
if ($validfrom && $validto) {
    $where .= " AND p.orderdate BETWEEN '$validfrom' AND '$validto'";
}

$sql = "SELECT 
            s.suppliername AS supplier,
            SUM(p.nettotal) AS total
        FROM tbl_porder p
        LEFT JOIN tbl_supplier s 
            ON p.tbl_supplier_idtbl_supplier = s.idtbl_supplier
        WHERE $where
        GROUP BY s.suppliername
        ORDER BY total DESC";

$result = $conn->query($sql);

// Debug error
if (!$result) {
    die("SQL Error: " . $conn->error . " | Query: " . $sql);
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "supplier" => $row['supplier'] ?: "Unknown",
        "total"    => (float)$row['total']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
