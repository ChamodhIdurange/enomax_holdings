<?php
include '../scripts/config.php';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

$searchTerm = $_POST['searchTerm'] ?? '';

$sql = "SELECT idtbl_customer, customer 
        FROM tbl_customer 
        WHERE status=1 AND customer LIKE ? 
        ORDER BY customer ASC";

$stmt = $conn->prepare($sql);
$likeTerm = "%{$searchTerm}%";
$stmt->bind_param("s", $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['idtbl_customer'], 
        "text" => $row['customer'] 
    ];
}

echo json_encode($data);
