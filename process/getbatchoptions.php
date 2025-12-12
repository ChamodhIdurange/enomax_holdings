<?php
require_once('../connection/db.php');

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$search = isset($_POST['searchTerm']) ? $conn->real_escape_string($_POST['searchTerm']) : '';

$sql = "
    SELECT idtbl_stock, batchno, qty AS available_qty 
    FROM tbl_stock 
    WHERE status=1 
      AND tbl_product_idtbl_product = $product_id 
      AND qty > 0
";

if (!empty($search)) {
    $sql .= " AND batchno LIKE '%$search%'";
}

$sql .= " LIMIT 10";

$result = $conn->query($sql);
$arraylist = [];

while ($row = $result->fetch_assoc()) {
    $obj = new stdClass();
    $obj->id = $row['idtbl_stock'];
    $obj->text = $row['batchno'] . " (Available: " . $row['available_qty'] . ")";
    $obj->available_qty = (float)$row['available_qty'];
    $arraylist[] = $obj;
}

echo json_encode($arraylist);
?>
