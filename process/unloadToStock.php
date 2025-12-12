<?php
require_once('../connection/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $loading_id = isset($input['id']) ? intval($input['id']) : 0;

    if ($loading_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid vehicle loading ID."]);
        exit;
    }


    // 1. Get remaining quantities for each product in this loading
    $sql = "SELECT vld.tbl_product_idtbl_product AS product_id, vld.qty_remaining
            FROM tbl_vehicle_loading_details vld
            WHERE vld.tbl_vehicle_loading_idtbl_vehicle_loading = ? AND vld.qty_remaining > 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loading_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $conn->begin_transaction();

    try {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $qty_to_return = $row['qty_remaining'];

            if ($qty_to_return > 0) {

                // ✅ 2. Get only the FIRST stock batch (lowest FIFO)
                $stockSql = "SELECT idtbl_stock, qty FROM tbl_stock
                             WHERE tbl_product_idtbl_product = ? AND status = 1
                             ORDER BY idtbl_stock ASC LIMIT 1";
                $stockStmt = $conn->prepare($stockSql);
                $stockStmt->bind_param("i", $product_id);
                $stockStmt->execute();
                $stockResult = $stockStmt->get_result();

                if ($stockResult->num_rows > 0) {
                    $stock = $stockResult->fetch_assoc();
                    $new_qty = $stock['qty'] + $qty_to_return;

                    // ✅ 3. Update only that stock row
                    $updateStock = "UPDATE tbl_stock SET qty = ? WHERE idtbl_stock = ?";
                    $updateStockStmt = $conn->prepare($updateStock);
                    $updateStockStmt->bind_param("di", $new_qty, $stock['idtbl_stock']);
                    $updateStockStmt->execute();
                }

                // ✅ 4. Set remaining quantity to zero (now fully unloaded)
                $updateDetails = "UPDATE tbl_vehicle_loading_details
                                  SET qty_remaining = 0
                                  WHERE tbl_vehicle_loading_idtbl_vehicle_loading = ?
                                  AND tbl_product_idtbl_product = ?";
                $updateDetailsStmt = $conn->prepare($updateDetails);
                $updateDetailsStmt->bind_param("ii", $loading_id, $product_id);
                $updateDetailsStmt->execute();
            }
        }

        // ✅ 5. Update main loading table total_remaining = 0, status = 4 (unloaded)
        $updateMain = "UPDATE tbl_vehicle_loading SET total_remaining = 0, status = 4 WHERE idtbl_vehicle_loading = ?";
        $updateMainStmt = $conn->prepare($updateMain);
        $updateMainStmt->bind_param("i", $loading_id);
        $updateMainStmt->execute();

        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Successfully unloaded remaining items to stock."]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
