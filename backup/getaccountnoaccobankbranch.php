<?php 
require_once('../connection/db.php');

$bankbranch=$_POST['bankbranch'];

$sql="SELECT `idtbl_bank_account`, `accountno` FROM `tbl_bank_account` WHERE `status`=1 AND `tbl_bank_branch_idtbl_bank_branch`='$bankbranch'";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->accountid=$row['idtbl_bank_account'];
    $obj->account=$row['accountno'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>