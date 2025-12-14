<?php
require_once('../connection/db.php');

$product_id = $_POST['product_id'];
$qty = $_POST['qty'];

// Get current month and day in MM-DD format
$currentMonthDay = date('m-d');

$sql = "SELECT 
        buy_quantity,
        free_quantity,
        start_date,
        end_date
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
        $start_date = $row['start_date']; // MM-DD format
        $end_date = $row['end_date'];     // MM-DD format
        
        // Check if current date falls within the range
        $isInRange = false;
        
        if ($start_date <= $end_date) {
            // Normal range (e.g., 03-15 to 06-20)
            $isInRange = ($currentMonthDay >= $start_date && $currentMonthDay <= $end_date);
        } else {
            // Range spans across year end (e.g., 11-15 to 02-28)
            $isInRange = ($currentMonthDay >= $start_date || $currentMonthDay <= $end_date);
        }
        
        // If in date range and quantity qualifies
        if ($isInRange && $qty >= $row['buy_quantity']) {
            $sets = floor($qty / $row['buy_quantity']);
            $freeQuantity = $sets * $row['free_quantity'];
            break; 
        }
    }
}

echo json_encode(["freequantity" => $freeQuantity]);
?>