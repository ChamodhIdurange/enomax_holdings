<?php 
require_once('../connection/db.php');


$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_size_categories` WHERE `idtbl_size_categories`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_size_categories'];
$obj->name=$row['name'];

echo json_encode($obj);
?>