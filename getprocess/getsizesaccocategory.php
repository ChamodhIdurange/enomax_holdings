<?php
require_once('../connection/db.php');

session_start();
$sizecategoryID=$_POST['sizecategoryID'];

$array = [];

$sql1="SELECT `p`.`idtbl_sizes`, `p`.`name` FROM `tbl_sizes` as `p` JOIN `tbl_size_categories` as `s` ON (`s`.`idtbl_size_categories` = `p`.`tbl_size_categories_idtbl_size_categories`) WHERE `p`.`tbl_size_categories_idtbl_size_categories` = '$sizecategoryID' and `p`.`status` = '1'";
$result1 =$conn-> query($sql1); 


while ($row = $result1-> fetch_assoc()) { 
    $obj=new stdClass();
    $obj->id=$row['idtbl_sizes'];
    $obj->name=$row['name'];
    array_push($array,$obj);
}


echo json_encode($array);
?>