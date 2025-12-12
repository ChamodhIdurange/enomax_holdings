<?php
header('Content-Type: application/json');
include '../scripts/config.php';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

include '../scripts/updateStockQty.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        throw new Exception("Invalid JSON input");
    }

    $conn->begin_transaction();

    $vehicle_id = (int)$data['tbl_vehicle_idtbl_vehicle'];
    $user_id = (int)$data['tbl_user_idtbl_user'];

    // Default main status to "1" if missing or empty
    $status = isset($data['status']) && $data['status'] !== '' ? $data['status'] : "1";
    $update_datetime = date('Y-m-d H:i:s');

    // Insert main vehicle loading record
    $sql1 = "INSERT INTO tbl_vehicle_loading 
                (tbl_vehicle_idtbl_vehicle, tbl_user_idtbl_user, status, update_datetime) 
             VALUES (?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("iiss", $vehicle_id, $user_id, $status, $update_datetime);
    $stmt1->execute();
    $vehicle_loading_id = $conn->insert_id;

    // Prepare reusable statements
    $sql2 = "INSERT INTO tbl_vehicle_loading_details 
                (tbl_vehicle_loading_idtbl_vehicle_loading, tbl_product_idtbl_product, qty, salesprice, unitprice, status, update_datetime) 
             VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);

    $sql3 = "INSERT INTO tbl_vehicle_loading_details_batches 
                (tbl_vehicle_loading_details_idtbl_vehicle_loading_details, tbl_batch_idtbl_batch, qty_from_batch, status, update_datetime) 
             VALUES (?, ?, ?, ?, ?)";
    $stmt3 = $conn->prepare($sql3);

    $total_items = 0;

    foreach ($data['vehicle_loading_details'] as $detail) {
        $product_id = (int)$detail['tbl_product_idtbl_product'];
        $qty = (float)$detail['qty'];
        $salesprice = (float)$detail['salesprice'];
        $unitprice = (float)$detail['unitprice'];
        $detail_status = isset($detail['status']) && $detail['status'] !== '' ? $detail['status'] : "1";

        $stmt2->bind_param(
            "iiiddss",
            $vehicle_loading_id,
            $product_id,
            $qty,
            $salesprice,
            $unitprice,
            $detail_status,
            $update_datetime
        );
        $stmt2->execute();
        $loading_details_id = $conn->insert_id;

        $total_items += $qty;

        // Loop through batches for this product
        if (!empty($detail['batches'])) {
            foreach ($detail['batches'] as $batch) {
                $batch_id = (int)$batch['tbl_batch_idtbl_batch'];
                $qty_from_batch = (float)$batch['qty_from_batch'];
                $batch_status = isset($batch['status']) && $batch['status'] !== '' ? $batch['status'] : "1";

                $stmt3->bind_param(
                    "iiiss",
                    $loading_details_id,
                    $batch_id,
                    $qty_from_batch,
                    $batch_status,
                    $update_datetime
                );
                $stmt3->execute();

                // ✅ Deduct stock immediately after saving each batch
                $deductResult = updateStockQty($conn, $product_id, $qty_from_batch);

                if ($deductResult !== true) {
                    throw new Exception($deductResult);
                }
            }
        }
    }

    // Update total items
    $sql_total = "UPDATE tbl_vehicle_loading SET total_items = ? WHERE idtbl_vehicle_loading = ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("ii", $total_items, $vehicle_loading_id);
    $stmt_total->execute();

    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'vehicle_loading_id' => $vehicle_loading_id
    ]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

mysqli_close($conn);
?>
