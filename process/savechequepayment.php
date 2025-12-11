<?php
include '../scripts/config.php';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

$cheque_number = $_POST['cheque_number'];
$idtbl_customer = $_POST['tbl_customer_idtbl_customer'] ?? null;
$amount = $_POST['amount'];
$bank = $_POST['tbl_bank_idtbl_bank'];
$branch = $_POST['tbl_bank_branch_idtbl_bank_branch'];
$payment_date = $_POST['payment_date'];

$branch = ($branch == 0 || empty($branch)) ? null : $branch;

$sql = "INSERT INTO tbl_cheque_payments 
        (cheque_number, tbl_customer_idtbl_customer, amount, tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch, payment_date, status, insertdatetime) 
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW())";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("siidis", $cheque_number, $idtbl_customer, $amount, $bank, $branch, $payment_date);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
?>
