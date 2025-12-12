<?php
include '../scripts/config.php';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

$searchTerm = $_POST['searchTerm'] ?? '';

$sql = "SELECT idtbl_bank, bankname 
        FROM tbl_bank 
        WHERE status = 1";

if (!empty($searchTerm)) {
    $sql .= " AND bankname LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeTerm = "%".$searchTerm."%";
    $stmt->bind_param("s", $likeTerm);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['idtbl_bank'],
        "text" => $row['bankname']
    ];
}

echo json_encode($data);
