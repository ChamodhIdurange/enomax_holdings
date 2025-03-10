<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_expences_type` WHERE `idtbl_expences_type`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_expences_type'];
$obj->expencestype=$row['expencestype'];

echo json_encode($obj);
?>