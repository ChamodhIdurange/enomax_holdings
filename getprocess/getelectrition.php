<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_electrician` WHERE `idtbl_electrician`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_electrician'];
$obj->name=$row['name'];
$obj->area=$row['tbl_area_idtbl_area'];
$obj->customer=$row['tbl_customer_idtbl_customer'];
$obj->contact=$row['contact'];
$obj->regno=$row['regno'];
$obj->dob=$row['dob'];
$obj->idnumber=$row['idnumber'];
$obj->whatsappno=$row['whatsappno'];
$obj->address=$row['address'];

echo json_encode($obj);
?>