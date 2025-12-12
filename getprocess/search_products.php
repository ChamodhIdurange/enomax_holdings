<?php
require_once('../connection/db.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$where = "WHERE 1=1";
if (!empty($search)) {
    $where .= " AND (product_code LIKE '%$search%' OR product_name LIKE '%$search%')";
}

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM tbl_product $where";
$count_result = mysqli_query($conn, $count_sql);
$total = mysqli_fetch_assoc($count_result)['total'];

// Get products
$sql = "SELECT 
    idtbl_product as id,
    CONCAT(product_code, ' - ', product_name) as text
FROM tbl_product 
$where
ORDER BY product_name
LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);

$data = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

$response = array(
    'results' => $data,
    'more' => ($offset + $limit) < $total
);

echo json_encode($response);
?>