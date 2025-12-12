<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}

require_once('../connection/db.php');

$id = $_POST['free_issue_id'];  

$sql = "UPDATE tbl_product_free_issue 
        SET status = 0, updated_at = NOW()
        WHERE tbl_product_free_issue_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Inactive successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Could not inactive"]);
}
?>
