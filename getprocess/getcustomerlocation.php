<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];


$sql="SELECT `a`.`idtbl_area`, `cl`.`idtbl_customer_location`, `cl`.`address`, `cl`.`ownername`, `cl`.`ownercontact`, `a`.`area` FROM `tbl_customer_location` as `cl` JOIN `tbl_customer` as `c` ON (`c`.`idtbl_customer` = `cl`.`tbl_customer_idtbl_customer`) JOIN `tbl_area` as `a` ON (`a`.`idtbl_area` = `cl`.`tbl_area_idtbl_area`) WHERE `cl`.`idtbl_customer_location` = '$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_customer_location'];
$obj->idarea=$row['idtbl_area'];
$obj->address=$row['address'];
$obj->ownername=$row['ownername'];
$obj->ownercontact=$row['ownercontact'];
$obj->area=$row['area'];


echo json_encode($obj);
?>