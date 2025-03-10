<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_locations` WHERE `idtbl_locations`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_locations'];
$obj->province=$row['province'];
$obj->district=$row['district'];
$obj->city=$row['city'];
$obj->location=$row['locationname'];
$obj->address=$row['address'];
$obj->contact1=$row['contact1'];
$obj->contact2=$row['contact2'];
$obj->contactperson=$row['contactperson'];
$obj->email=$row['email'];
$obj->headperson=$row['headperson'];
$obj->bank=$row['tbl_bank_idtbl_bank'];
$obj->accountowner=$row['accountowner'];
$obj->accountnumber=$row['accountnumber'];

echo json_encode($obj);
?>