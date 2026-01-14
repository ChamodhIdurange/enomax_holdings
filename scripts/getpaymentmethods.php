<?php
require_once('../config.php');

$id = intval($_POST['id'] ?? 0);

$sql = "SELECT m.method_name, d.amount, d.chequeno, d.chequedate
        FROM tbl_invoice_payment_detail d
        LEFT JOIN payment_methods m ON m.id = d.method
        WHERE d.tbl_invoice_payment_idtbl_invoice_payment = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
