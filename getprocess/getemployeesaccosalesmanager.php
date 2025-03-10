<?php
require_once('../connection/db.php');

session_start();
$salesmanagerid=$_POST['salesmanagerid'];

$array = [];

$sql="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_sales_manager_idtbl_sales_manager` = '$salesmanagerid' AND `tbl_user_type_idtbl_user_type`=7 AND `status` = '1'";
$result =$conn-> query($sql); 


while ($row = $result-> fetch_assoc()) { 
    $obj=new stdClass();
    $obj->id=$row['idtbl_employee'];
    $obj->name=$row['name'];
    array_push($array,$obj);
}


echo json_encode($array);
?>