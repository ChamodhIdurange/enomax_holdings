<?php
require_once('../connection/db.php');

if (!isset($_POST['searchTerm'])) {
    $sql = "SELECT idtbl_user, name FROM tbl_user LIMIT 10";
} else {
    $search = $conn->real_escape_string($_POST['searchTerm']);
    $sql = "SELECT idtbl_user, name FROM tbl_user
            WHERE role='driver' AND name LIKE '%$search%' LIMIT 10";
}

$result = $conn->query($sql);
$arraylist = [];

while ($row = $result->fetch_assoc()) {
    $obj = new stdClass();
    $obj->id = $row['idtbl_user'];
    $obj->text = $row['name'];
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>
