<?php 
require_once('../connection/db.php');


$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_sales_manager` WHERE `idtbl_sales_manager`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_sales_manager'];
$obj->name=$row['salesmanagername'];
$obj->contactone=$row['contactone'];
$obj->email=$row['email'];
$obj->address=$row['address'];
$obj->useraccount=$row['tbl_user_idtbl_user'];

echo json_encode($obj);
?>