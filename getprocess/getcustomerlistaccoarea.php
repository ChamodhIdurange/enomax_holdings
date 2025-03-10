<?php 
require_once('../connection/db.php');

$areaID=$_POST['areaID'];
$repId=$_POST['repId'];

$sql="SELECT `idtbl_customer`, `name`, `address` FROM `tbl_customer` WHERE `status`=1 AND `tbl_area_idtbl_area`='$areaID' AND `ref` = '$repId'";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_customer']; 
    $obj->name=$row['name'];
    $obj->address=$row['address'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>