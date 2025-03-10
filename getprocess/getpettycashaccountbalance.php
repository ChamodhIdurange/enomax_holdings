<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT `balance` FROM `tbl_pettycash_account_balance` WHERE `tbl_account_petty_cash_account`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->balance=$row['balance'];
echo json_encode($obj);
?>