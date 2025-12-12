<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if(!isset($_SESSION['userid'])){
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit;
}

require_once('../connection/db.php');

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

$sql = "SELECT 
        fi.tbl_product_free_issue_id,
        fi.product_id,
        fi.buy_quantity,
        fi.free_quantity,
        DATE_FORMAT(fi.start_date, '%Y-%m-%d') AS start_date,
        DATE_FORMAT(fi.end_date, '%Y-%m-%d') AS end_date,
        fi.status,
        p.product_name
    FROM tbl_product_free_issue fi
    LEFT JOIN tbl_product p ON fi.product_id = p.idtbl_product
    WHERE fi.tbl_product_free_issue_id = ?
    LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "data" => $row]);
} else {
    echo json_encode(["success" => false, "message" => "Record not found"]);
}
