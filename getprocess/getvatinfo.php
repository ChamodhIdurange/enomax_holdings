<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_vat_info` WHERE `idtbl_vat_info`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_vat_info'];
$obj->fromdate=$row['date_from'];
$obj->vat=$row['vat'];
$obj->nbt=$row['nbt'];
$obj->s_vat=$row['s_vat'];

echo json_encode($obj);
?>