<?php
include '../scripts/config.php';

// Create DB connection using variables from config.php
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(['error' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

header('Content-Type: application/json');

if (!isset($_GET['bank_id'])) {
    echo json_encode([]);
    exit;
}

$bank_id = intval($_GET['bank_id']);

$stmt = $conn->prepare("SELECT idtbl_bank_branch, branchname FROM tbl_bank_branch WHERE tbl_bank_idtbl_bank = ? AND status = 1");
$stmt->bind_param("i", $bank_id);
$stmt->execute();

$result = $stmt->get_result();
$branches = [];

while ($row = $result->fetch_assoc()) {
    $branches[] = $row;
}

echo json_encode($branches);
?>
