<?php

require_once('../connection/db.php');
$q    = isset($_GET['q'])    ? trim($_GET['q'])   : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20;
$offset  = ($page - 1) * $perPage;
$search = '%' . $conn->real_escape_string($q) . '%';
/* Total count */
$countSql = "SELECT COUNT(*) AS cnt FROM tbl_customer
             WHERE status = 1 AND (customer LIKE '$search' OR idtbl_customer LIKE '$search')";
$countRes = $conn->query($countSql);
$total    = (int)$countRes->fetch_assoc()['cnt'];
/* Results */
$dataSql = "SELECT idtbl_customer AS id, customer AS text
            FROM tbl_customer
            WHERE status = 1 AND (customer LIKE '$search' OR idtbl_customer LIKE '$search')
            ORDER BY customer ASC
            LIMIT $perPage OFFSET $offset";
$dataRes = $conn->query($dataSql);
$results = [];
while ($row = $dataRes->fetch_assoc()) {
    $results[] = ['id' => $row['id'], 'text' => $row['text']];
}
header('Content-Type: application/json');
echo json_encode([
    'results'    => $results,
    'pagination' => ['more' => ($offset + $perPage) < $total]
]);