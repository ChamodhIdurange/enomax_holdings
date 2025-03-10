<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_user` WHERE `idtbl_user`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_user'];
$obj->name=$row['name'];
$obj->username=$row['username'];
$obj->type=$row['tbl_user_type_idtbl_user_type'];

echo json_encode($obj);
?>