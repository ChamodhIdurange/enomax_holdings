<?php 
require_once('../connection/db.php');

$repId=$_POST['repId'];

$sql="SELECT `e`.`idtbl_area`, `e`.`area` FROM `tbl_area` AS `e` JOIN `tbl_employee_area` AS `a` ON (`a`.`tbl_area_idtbl_area` = `e`.`idtbl_area`) WHERE `e`.`status`=1 AND `a`.`tbl_employee_idtbl_employee` = '$repId'";
$result=$conn->query($sql);

$arraylist=array();

while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_area'];
    $obj->name=$row['area'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>