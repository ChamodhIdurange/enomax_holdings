<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}
require_once('../connection/db.php');

$id = $_POST['free_issue_id'];
$action = isset($_POST['action']) ? $_POST['action'] : 'inactive'; // Default to inactive

if ($action === 'delete') {
    $status = 3; // Delete status
    $message = "Deleted successfully";
    $errorMessage = "Could not delete";
} else {
    $status = 0; 
    $message = "Inactivated successfully";
    $errorMessage = "Could not inactivate";
}

$sql = "UPDATE tbl_product_free_issue 
        SET status = ?, updated_at = NOW()
        WHERE tbl_product_free_issue_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $status, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => $message]);
} else {
    echo json_encode(["success" => false, "message" => $errorMessage]);
}

$stmt->close();
$conn->close();
?>