<?php 
require_once('../connection/db.php');

$bank=$_POST['bank'];

$sql="SELECT `idtbl_bank_branch`, `branchname` FROM `tbl_bank_branch` WHERE `status`=1 AND `tbl_bank_idtbl_bank`='$bank'";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->branchid=$row['idtbl_bank_branch'];
    $obj->branch=$row['branchname'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>