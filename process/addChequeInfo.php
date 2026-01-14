<?php
include "../include/connection.php"; // adjust if needed
session_start();

$response = ['success' => false];

// Validate input
if (
    isset($_POST['startno'], $_POST['endno'], $_POST['bank'], $_POST['branch'], $_POST['account'])
    && is_numeric($_POST['startno']) && is_numeric($_POST['endno'])
) {
    $startno = $_POST['startno'];
    $endno = $_POST['endno'];
    $bank = $_POST['bank'];
    $branch = $_POST['branch'];
    $account = $_POST['account'];
    $userid = $_SESSION['user_id'] ?? 1; // fallback to 1 if session not set

    $stmt = $conn->prepare("INSERT INTO tbl_cheque_info 
        (startno, endno, tbl_bank_idtbl_bank, tbl_bank_branch_idtbl_bank_branch, tbl_account_idtbl_account, tbl_user_idtbl_user, updateuser, updatedatetime, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1)");
    
    $updateuser = 'admin'; // or from session
    if ($stmt->execute([$startno, $endno, $bank, $branch, $account, $userid, $updateuser])) {
        $response['success'] = true;
    } else {
        $response['message'] = 'DB insert failed.';
    }
} else {
    $response['message'] = 'Missing or invalid input.';
}

echo json_encode($response);
