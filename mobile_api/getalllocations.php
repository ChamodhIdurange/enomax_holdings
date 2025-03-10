<?php 
require_once('../connection/db.php');


$sql="SELECT * FROM `tbl_locations` WHERE `status`='1'";
$result=$conn->query($sql);
$array = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($array, array( "id" => $row['idtbl_locations'], "locationname" => $row['locationname']));
}

print(json_encode($array));

?>