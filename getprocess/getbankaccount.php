<?php 
require_once('../connection/db.php');

$record = $_POST['recordID'];

$sql = "SELECT `idtbl_account`, `account`, `accountno` FROM `tbl_account` WHERE `tbl_bank_idtbl_bank`='$record'";
$result = $conn->query($sql);

$accounts = array();
while ($row = $result->fetch_assoc()) {
    $accounts[] = $row;
}

echo json_encode($accounts);
?>
