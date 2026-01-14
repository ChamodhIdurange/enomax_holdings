<?php
require_once('../connection/db.php');

if (!isset($_POST['searchTerm'])) {
    $sql = "SELECT idtbl_vehicle, vehicleno FROM tbl_vehicle WHERE status='1' LIMIT 10";
} else {
    $search = $conn->real_escape_string($_POST['searchTerm']);
    $sql = "SELECT idtbl_vehicle, vehicleno FROM tbl_vehicle 
            WHERE status= '1' AND vehicleno LIKE '%$search%' LIMIT 10";
}

$result = $conn->query($sql);

if (!$result) {
    die("❌ SQL Error: " . $conn->error . " | Query: " . $sql);
}

$arraylist = [];

while ($row = $result->fetch_assoc()) {
    $obj = new stdClass();
    $obj->id = $row['idtbl_vehicle'];
    $obj->text = $row['vehicleno'];
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>
