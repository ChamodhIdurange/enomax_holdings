<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['userid'])) {
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit;
}
$userID = $_SESSION['userid'];

require_once('../connection/db.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$buy_quantity = isset($_POST['buy_quantity']) ? (int) $_POST['buy_quantity'] : 0;
$free_quantity = isset($_POST['free_quantity']) ? (int) $_POST['free_quantity'] : 0;
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
$status = isset($_POST['status']) ? 1 : 0;
$id = isset($_POST['free_issue_id']) && $_POST['free_issue_id'] !== '' ? (int) $_POST['free_issue_id'] : 0;

if ($product_id <= 0 || $buy_quantity <= 0 || $free_quantity <= 0 || !$start_date || !$end_date) {
    echo json_encode(["success" => false, "message" => "Missing or invalid inputs"]);
    exit;
}

$sqlCheck = "SELECT 1 FROM tbl_product_free_issue
            WHERE product_id = ?
            AND status = 1
            AND (start_date <= ? AND end_date >= ?)
            AND tbl_product_free_issue_id != ? LIMIT 1";

$stmt = $conn->prepare($sqlCheck);
$new_end = $end_date;
$new_start = $start_date;
$stmt->bind_param("issi", $product_id, $new_end, $new_start, $id);
$stmt->execute();
$checkResult = $stmt->get_result();

if ($checkResult && $checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Date range overlaps with existing record for this product"]);
    exit;
}

if ($action === "add") {
    $sql = "INSERT INTO tbl_product_free_issue 
       (product_id, buy_quantity, free_quantity, start_date, end_date, status, created_at, updated_at, updated_by)
       VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissii", $product_id, $buy_quantity, $free_quantity, $start_date, $end_date, $status, $userID);


    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Free issue added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding data: " . $stmt->error]);
    }
} elseif ($action === "edit") {
    if ($id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid record id"]);
        exit;
    }
    $sql = "UPDATE tbl_product_free_issue SET
            product_id=?, buy_quantity=?, free_quantity=?, start_date=?, end_date=?, status=?, updated_at=NOW(), updated_by=?
            WHERE tbl_product_free_issue_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissiii", $product_id, $buy_quantity, $free_quantity, $start_date, $end_date, $status, $userID, $id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed: " . $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid action"]);
}
