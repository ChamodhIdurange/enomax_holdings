<?php 
require_once('../connection/db.php');


$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_sizes` WHERE `idtbl_sizes`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_sizes'];
$obj->name=$row['name'];
$obj->sequence=$row['sequence'];
$obj->category=$row['tbl_size_categories_idtbl_size_categories'];

echo json_encode($obj);
?>