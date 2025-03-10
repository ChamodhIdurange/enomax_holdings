<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_user_privilege` WHERE `idtbl_user_privilege`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$menulistArray=array();
$objmenulist=new stdClass();
$objmenulist->menulistID=$row['tbl_menu_list_idtbl_menu_list'];
array_push($menulistArray, $objmenulist);

$obj=new stdClass();
$obj->id=$row['idtbl_user_privilege'];
$obj->add=$row['add'];
$obj->edit=$row['edit'];
$obj->statuschange=$row['statuschange'];
$obj->remove=$row['remove'];
$obj->user=$row['tbl_user_idtbl_user'];
$obj->menu=$menulistArray;

echo json_encode($obj);
?>