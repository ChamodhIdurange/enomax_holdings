<?php
require_once('../connection/db.php');

$record=$_POST['orderID'];

$sql="SELECT * FROM `tbl_dispatch` WHERE `porder_id`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$arrayhelper=array();
$sqlhelper="SELECT `tbl_employee_idtbl_employee` FROM `tbl_employee_has_tbl_dispatch` WHERE `tbl_dispatch_idtbl_dispatch`='$record'";
$resulthelper=$conn->query($sqlhelper);
while($rowhelper=$resulthelper->fetch_assoc()){
    $objhelper=new stdClass();
    $objhelper->herlperID=$rowhelper['tbl_employee_idtbl_employee'];

    array_push($arrayhelper, $objhelper);
}

if($result->num_rows>0){
    $obj=new stdClass();
    $obj->id=$row['idtbl_dispatch'];
    $obj->driverid=$row['driver_id'];
    $obj->officerid=$row['officer_id'];
    $obj->helperid=$arrayhelper;
}
else{
    $obj=new stdClass();
    $obj->id='0';
}

echo json_encode($obj);
?>