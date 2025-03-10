<?php 
require_once('../connection/db.php');

$invdate=$_POST['invdate'];
$refID=$_POST['refID'];

$sql="SELECT `idtbl_vehicle_load` FROM `tbl_vehicle_load` WHERE `refid`='$refID' AND `status`=1 AND `date`='$invdate' AND `approvestatus`=1 AND `unloadstatus`=0";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_vehicle_load'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>