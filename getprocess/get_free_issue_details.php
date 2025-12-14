<?php
require_once('../connection/db.php');

$id = $_POST['id'];

$sql = "SELECT 
        fi.tbl_product_free_issue_id,
        fi.product_id,
        fi.buy_quantity,
        fi.free_quantity,
        fi.start_date,
        fi.end_date,
        fi.status,
        p.product_name,
        p.product_code
        FROM tbl_product_free_issue fi
        INNER JOIN tbl_product p ON fi.product_id = p.idtbl_product
        WHERE fi.tbl_product_free_issue_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "data" => $data
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Record not found"
    ]);
}

$stmt->close();
$conn->close();
?>