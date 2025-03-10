<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_user_type` WHERE `idtbl_user_type`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_user_type'];
$obj->type=$row['type'];

echo json_encode($obj);
?>