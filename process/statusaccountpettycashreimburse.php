<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$record=$_GET['record'];
$type=$_GET['type'];

if($type==3){$value=3;}

$sql="UPDATE `tbl_pettycash_reimburse` SET `status`='$value',`updatedatetime`='$updatedatetime' WHERE `idtbl_pettycash_reimburse`='$record'";

$selectreimburse = "SELECT `amount`, `tbl_account_petty_cash_account` FROM `tbl_pettycash_reimburse` WHERE `idtbl_pettycash_reimburse`='$record'";
$result = $conn->query($selectreimburse);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $amount = $row['amount'];
    $pettyCashAccount = $row['tbl_account_petty_cash_account'];

    $updatebalance="UPDATE `tbl_pettycash_account_balance` SET `balance` = `balance`-'$amount' WHERE `tbl_account_petty_cash_account` = '$pettyCashAccount'";
    $conn->query($updatebalance);
}

if($conn->query($sql)==true){
    header("Location:../accountpettycashreimburse.php?action=6");
}
else{
    header("Location:../accountpettycashreimburse.php?action=5");
}
?>