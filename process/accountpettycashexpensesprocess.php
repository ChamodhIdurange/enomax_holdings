<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$pettyCashAccount=$_POST['pettyCashAccount'];
$expensesAccount=$_POST['expensesAccount'];
$amount = floatval(str_replace(',', '', $_POST['amount']));
$narration=$_POST['narration'];
$updatedatetime=date('Y-m-d h:i:s');


$insert="INSERT INTO `tbl_pettycash_expenses`
        ( `amount`, `narration`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_account_petty_cash_account`, `tbl_account_expenses_account`) VALUES 
        ('$amount', '$narration', '1','$updatedatetime','$userID', '$pettyCashAccount', '$expensesAccount')";
$conn->query($insert);

$updatebalance="UPDATE `tbl_pettycash_account_balance` SET `balance` = `balance`-'$amount' WHERE `tbl_account_petty_cash_account` = '$pettyCashAccount'";

if($conn->query($updatebalance)==true){        
    header("Location:../accountpettycashexpenses.php?action=4");
}
else{
    header("Location:../accountpettycashexpenses.php?action=5");
}

?>