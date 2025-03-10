<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];
$type=$_POST['type'];

$sql="SELECT `b`.`idtbl_bank`, `b`.`code`,`a`.`branchname`,`a`.`accountnumber`, `a`.`status`, `a`.`idtbl_bank_account_details`, `a`.`account_name` FROM `tbl_bank` as `b` JOIN `tbl_bank_account_details` AS `a` ON (`a`.`tbl_bank_idtbl_bank` = `b`.`idtbl_bank`) WHERE `a`.`idtbl_bank_account_details`='$record' and `a`.`type` = '$type'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_bank_account_details'];
$obj->bankname=$row['idtbl_bank'];
$obj->branchname=$row['branchname'];
$obj->accountnumber=$row['accountnumber'];
$obj->accountname=$row['account_name'];


echo json_encode($obj);
?>