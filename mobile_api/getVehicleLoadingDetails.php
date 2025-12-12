<?php
require_once('dbConnect.php');
header("Content-Type: application/json");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$response = [];

if ($id > 0) {
    // Fetch main vehicle loading record
    $sql = "SELECT * FROM tbl_vehicle_loading WHERE idtbl_vehicle_loading = ?";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed for main query.", "db_error" => $con->error]);
        exit;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $loadingResult = $stmt->get_result();

    if ($loading = $loadingResult->fetch_assoc()) {
        // Fetch details + product names
        $sql2 = "
            SELECT 
                d.idtbl_vehicle_loading_details,
                d.tbl_vehicle_loading_idtbl_vehicle_loading,
                d.tbl_product_idtbl_product,
                p.common_name AS productname,
                d.qty,
                IFNULL(d.qty_remaining, 0) AS qty_remaining
            FROM tbl_vehicle_loading_details d
            LEFT JOIN tbl_product p ON p.idtbl_product = d.tbl_product_idtbl_product
            WHERE d.tbl_vehicle_loading_idtbl_vehicle_loading = ?
        ";
        $stmt2 = $con->prepare($sql2);

        if (!$stmt2) {
            echo json_encode(["status" => "error", "message" => "Prepare failed for vehicle loading details (sql2).", "db_error" => $con->error]);
            exit;
        }

        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $detailsResult = $stmt2->get_result();

        $details = [];
        while ($detail = $detailsResult->fetch_assoc()) {
            $detail_id = $detail['idtbl_vehicle_loading_details'];

            // Fetch related batches
            $sql3 = "
                SELECT 
                    b.idtbl_vehicle_loading_details_batches,
                    b.tbl_batch_idtbl_batch,
                    s.batchno,
                    b.qty_from_batch,
                    IFNULL(b.qty_returned, 0) AS qty_returned
                FROM tbl_vehicle_loading_details_batches b
                LEFT JOIN tbl_stock s ON s.idtbl_stock = b.tbl_batch_idtbl_batch
                WHERE b.tbl_vehicle_loading_details_idtbl_vehicle_loading_details = ?
            ";

            $stmt3 = $con->prepare($sql3);

            if (!$stmt3) {
                echo json_encode(["status" => "error", "message" => "Prepare failed for batch query (sql3).", "db_error" => $con->error]);
                exit;
            }

            $stmt3->bind_param("i", $detail_id);
            $stmt3->execute();
            $batchesResult = $stmt3->get_result();

            $batches = [];
            while ($batch = $batchesResult->fetch_assoc()) {
                $batches[] = $batch;
            }

            $detail['batches'] = $batches;
            $details[] = $detail;
        }

        $response = [
            "status" => "ok",
            "vehicle_loading_id" => $id,
            "details" => $details
        ];
    } else {
        $response = ["status" => "error", "message" => "Vehicle loading record not found."];
    }

} else {
    $response = ["status" => "error", "message" => "Invalid vehicle loading ID."];
}

echo json_encode($response);
mysqli_close($con);
