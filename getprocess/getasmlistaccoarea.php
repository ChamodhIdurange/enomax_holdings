<?php 
require_once('../connection/db.php');

$areaID=$_POST['areaID'];

$sql="SELECT `e`.`idtbl_employee`, `e`.`name` FROM `tbl_employee` AS `e` JOIN `tbl_employee_area` AS `a` ON (`a`.`tbl_employee_idtbl_employee` = `e`.`idtbl_employee`) WHERE `e`.`status`=1 AND `e`.`tbl_user_type_idtbl_user_type` in ('7','8','9','10','11') AND `a`.`tbl_area_idtbl_area` = '$areaID'";
$result=$conn->query($sql);


$arraylist=array();


while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_employee'];
    $obj->name=$row['name'];
    
    array_push($arraylist, $obj);
}



echo json_encode($arraylist);
?>