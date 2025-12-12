<?php
require_once('../connection/db.php');
header('Content-Type: application/json');

if (!isset($_POST['idtbl_vehicle_loading']) || !isset($_POST['status'])) {
    echo json_encode(["success" => false, "message" => "Invalid parameters."]);
    exit;
}

$id = intval($_POST['idtbl_vehicle_loading']);
$status = intval($_POST['status']);

// Valid statuses: 0=Pending,1=Loaded,2=In Transit,3=Completed,4=Deleted
if (!in_array($status, [0, 1, 2, 3, 4])) {
    echo json_encode(["success" => false, "message" => "Invalid status value."]);
    exit;
}

$sql = "UPDATE tbl_vehicle_loading 
        SET status = '$status', update_datetime = NOW()
        WHERE idtbl_vehicle_loading = '$id'";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}
?>
