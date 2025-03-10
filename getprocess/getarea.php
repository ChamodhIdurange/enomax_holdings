<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_area` WHERE `idtbl_area`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_area'];
$obj->area=$row['area'];
$obj->district=$row['tbl_district_idtbl_district'];


echo json_encode($obj);
?>