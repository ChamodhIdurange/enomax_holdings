<?php
// Include your database connection
require_once('../connection/db.php');


header('Content-Type: application/json');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

$searchParam = "%{$search}%";

$countSql = "SELECT COUNT(*) as total FROM `tbl_customer` WHERE `status` = 1";
if (!empty($search)) {
    $countSql .= " AND `customer` LIKE ?";
}

$countStmt = $conn->prepare($countSql);
if (!empty($search)) {
    $countStmt->bind_param("s", $searchParam);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalCount = $countResult->fetch_assoc()['total'];
$countStmt->close();

$sql = "SELECT `idtbl_customer`, `customer` FROM `tbl_customer` WHERE `status` = 1";
if (!empty($search)) {
    $sql .= " AND `customer` LIKE ?";
}
$sql .= " ORDER BY `customer` LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $stmt->bind_param("sii", $searchParam, $perPage, $offset);
} else {
    $stmt->bind_param("ii", $perPage, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = [
        'id' => $row['idtbl_customer'],
        'text' => $row['customer']
    ];
}

$stmt->close();

$more = ($offset + $perPage) < $totalCount;

echo json_encode([
    'results' => $customers,
    'more' => $more
]);
