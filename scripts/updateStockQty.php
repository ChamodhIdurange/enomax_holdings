<?php
/**
 * Deduct product quantities from tbl_stock in FIFO order.
 * 
 * @param mysqli $conn - active DB connection
 * @param int $product_id - product ID
 * @param float|int $required_qty - total quantity to deduct
 * @return bool|string - true on success, error message on failure
 */
function updateStockQty($conn, $product_id, $required_qty)
{
    if ($required_qty <= 0) {
        return "Invalid quantity.";
    }

    // Start transaction for safety
    $conn->begin_transaction();

    try {
        // Fetch available stock records in FIFO order
        $sql = "
            SELECT idtbl_stock, qty 
            FROM tbl_stock 
            WHERE tbl_product_idtbl_product = ? 
              AND status = 1 
              AND qty > 0
            ORDER BY idtbl_stock ASC
            FOR UPDATE
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $remaining = $required_qty;

        while ($row = $result->fetch_assoc()) {
            if ($remaining <= 0) break;

            $stock_id = $row['idtbl_stock'];
            $available = (float)$row['qty'];

            if ($available >= $remaining) {
                // Deduct partially or fully from this batch
                $new_qty = round($available - $remaining, 4);
                $update = $conn->prepare("UPDATE tbl_stock SET qty = ? WHERE idtbl_stock = ?");
                $update->bind_param("di", $new_qty, $stock_id);
                $update->execute();
                $update->close();
                $remaining = 0;
            } else {
                // Use up this batch completely
                $update = $conn->prepare("UPDATE tbl_stock SET qty = 0 WHERE idtbl_stock = ?");
                $update->bind_param("i", $stock_id);
                $update->execute();
                $update->close();
                $remaining -= $available;
            }
        }

        $stmt->close();

        // Not enough stock available
        if ($remaining > 0) {
            $conn->rollback();
            return "Not enough stock available for product ID: {$product_id}";
        }

        // Commit the stock update
        $conn->commit();
        return true;

    } catch (Exception $e) {
        $conn->rollback();
        return "Stock deduction failed: " . $e->getMessage();
    }
}
?>
