<?php
include '../scripts/config.php';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

if(isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);

    $sql = "UPDATE tbl_cheque_payments SET status = ? WHERE idtbl_cheque_payments = ?";
    $stmt = $conn->prepare($sql);

    if(!$stmt) {
        // Output the SQL error
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("ii", $status, $id);

    if($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
