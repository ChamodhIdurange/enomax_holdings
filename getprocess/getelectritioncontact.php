<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];
$type=$_POST['type'];

$sql="SELECT * FROM `tbl_contact_details` WHERE `idtbl_contact_details`='$record' and `type` = '$type'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_contact_details'];
$obj->name=$row['contact_owner'];
$obj->relation=$row['relation'];
$obj->number=$row['number'];
$obj->type=$row['type'];
$obj->email=$row['email'];

echo json_encode($obj);
?>