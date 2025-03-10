<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_employee` WHERE `idtbl_employee`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$array = [];

$sql2="SELECT * FROM `tbl_employee_area` WHERE `tbl_employee_idtbl_employee`='$record'";
$result2=$conn->query($sql2);


while ($row2 = $result2-> fetch_assoc()) { 
    $obj=new stdClass();
    $obj->areaid=$row2['tbl_area_idtbl_area'];
    array_push($array,$obj);
}

$obj=new stdClass();
$obj->id=$row['idtbl_employee'];
$obj->name=$row['name'];
$obj->epfno=$row['epfno'];
$obj->nic=$row['nic'];
$obj->phone=$row['phone'];
$obj->address=$row['address'];
$obj->emptype=$row['tbl_user_type_idtbl_user_type'];
$obj->salesmanager=$row['tbl_sales_manager_idtbl_sales_manager'];
$obj->useraccountid=$row['useraccountid'];
$obj->array=$array;

echo json_encode($obj);
?>