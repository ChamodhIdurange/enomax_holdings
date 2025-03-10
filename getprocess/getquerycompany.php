<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_query_company` WHERE `idtbl_query_company`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();


$obj=new stdClass();
$obj->id=$row['idtbl_query_company'];
$obj->name=$row['name'];
$obj->location=$row['location'];
$obj->email=$row['email'];
$obj->contact=$row['contact'];
$obj->headperson=$row['headperson'];


echo json_encode($obj);
?>