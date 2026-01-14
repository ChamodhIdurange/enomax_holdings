<?php
require_once('../connection/db.php');

// Search term
$search = isset($_POST['searchTerm']) ? $conn->real_escape_string($_POST['searchTerm']) : '';

$sql = "
    SELECT 
        p.idtbl_product,
        p.product_name,
        IFNULL(SUM(s.qty), 0) AS available_qty
    FROM tbl_product p
    INNER JOIN tbl_stock s ON s.tbl_product_idtbl_product = p.idtbl_product
    WHERE p.status = 1 
      AND s.status = 1
      AND s.qty > 0
";

if (!empty($search)) {
    $sql .= " AND p.product_name LIKE '%$search%'";
}

$sql .= " GROUP BY p.idtbl_product HAVING available_qty > 0 LIMIT 10";

$result = $conn->query($sql);
$arraylist = [];

while ($row = $result->fetch_assoc()) {
    $obj = new stdClass();
    $obj->id = $row['idtbl_product'];
    $obj->text = $row['product_name'] . " (Available: " . $row['available_qty'] . ")";
    $obj->available_qty = (float)$row['available_qty'];
    $arraylist[] = $obj;
}

echo json_encode($arraylist);
?>
