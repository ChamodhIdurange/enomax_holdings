<?php
require_once('../connection/db.php');

$product_id = $_POST['product_id'];
$qty = $_POST['qty'];

$sql = "SELECT 
        buy_quantity,
        free_quantity
        FROM tbl_product_free_issue
        WHERE product_id = ? 
        AND status = 1
        ORDER BY buy_quantity DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$freeQuantity = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($qty >= $row['buy_quantity']) {
            $sets = floor($qty / $row['buy_quantity']);
            $freeQuantity = $sets * $row['free_quantity'];
            break; 
        }
    }
}

echo json_encode(["freequantity" => $freeQuantity]);
?>