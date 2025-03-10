<?php 
require_once('../connection/db.php');

$company=$_POST['company'];

$sql="SELECT `idtbl_company_branch`, `code`, `branch` FROM `tbl_company_branch` WHERE `status`=1 AND `tbl_company_idtbl_company`='$company'";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_company_branch'];
    $obj->code=$row['code'];
    $obj->branch=$row['branch'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>