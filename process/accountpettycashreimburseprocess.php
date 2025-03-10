<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$pettyCashAccount=$_POST['pettyCashAccount'];
$bank=$_POST['bank'];
$bankAccount=$_POST['bankAccount'];
$amount = floatval(str_replace(',', '', $_POST['amount']));
$updatedatetime=date('Y-m-d h:i:s');

$insert="INSERT INTO `tbl_pettycash_reimburse`
        (`amount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_account_petty_cash_account`, `tbl_bank_idtbl_bank`, `tbl_account_bank_account`) VALUES 
        ('$amount', '1','$updatedatetime','$userID', '$pettyCashAccount', '$bank', '$bankAccount')";

$selectbalance="SELECT `balance` FROM `tbl_pettycash_account_balance` WHERE `tbl_account_petty_cash_account` = '$pettyCashAccount'";
$result = $conn->query($selectbalance);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $balance = floatval($row['balance']);

    $newbalance = $balance + $amount;

    $updatebalance="UPDATE `tbl_pettycash_account_balance` SET `balance` = '$newbalance' WHERE `tbl_account_petty_cash_account` = '$pettyCashAccount'";
    $conn->query($updatebalance);
}
else {
    $insertbalance="INSERT INTO `tbl_pettycash_account_balance` (`balance`, `tbl_account_petty_cash_account`) VALUES ('$amount','$pettyCashAccount')";
    $conn->query($insertbalance);
}

if($conn->query($insert)==true){        
    header("Location:../accountpettycashreimburse.php?action=4");
}
else{
    header("Location:../accountpettycashreimburse.php?action=5");
}

?>