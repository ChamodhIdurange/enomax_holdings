<?php
require_once('../connection/db.php');
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents("php://input"), true);

    $id = isset($input['id']) ? intval($input['id']) : 0;
    $data = isset($input['data']) ? $input['data'] : [];

    if ($id <= 0 || empty($data)) {
        echo json_encode(["status" => "error", "message" => "Invalid input data."]);
        exit;
    }

    $conn->begin_transaction();

    // ✅ 1. Update remaining qty per product
    foreach ($data as $key => $value) {
        if (strpos($key, 'remaining[') === 0) {
            $productId = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
            $remainingQty = (float) $value;

            $sql = "UPDATE tbl_vehicle_loading_details 
                    SET qty_remaining = ? 
                    WHERE tbl_vehicle_loading_idtbl_vehicle_loading = ? 
                      AND tbl_product_idtbl_product = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("dii", $remainingQty, $id, $productId);
            $stmt->execute();
        }
    }

    // ✅ 2. Calculate total remaining across all details
    $checkSql = "SELECT COALESCE(SUM(qty_remaining), 0) AS total_remaining 
                 FROM tbl_vehicle_loading_details 
                 WHERE tbl_vehicle_loading_idtbl_vehicle_loading = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result()->fetch_assoc();
    $totalRemaining = (float) ($checkResult['total_remaining'] ?? 0);

    // ✅ 3. Update total_remaining column in tbl_vehicle_loading
    $updateRemainingSql = "UPDATE tbl_vehicle_loading 
                           SET total_remaining = ? 
                           WHERE idtbl_vehicle_loading = ?";
    $updateRemainingStmt = $conn->prepare($updateRemainingSql);
    $updateRemainingStmt->bind_param("di", $totalRemaining, $id);
    $updateRemainingStmt->execute();

    // ✅ 4. If all unloaded (total_remaining = 0), mark as complete
    if ($totalRemaining <= 0) {
        $updateStatusSql = "UPDATE tbl_vehicle_loading 
                            SET status = 4 
                            WHERE idtbl_vehicle_loading = ?";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bind_param("i", $id);
        $updateStatusStmt->execute();
    }

    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Remaining quantities and total updated successfully.",
        "total_remaining" => $totalRemaining
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
