<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_vehicle` WHERE `idtbl_vehicle`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_vehicle'];
$obj->vehicleno=$row['vehicleno'];

echo json_encode($obj);
?>