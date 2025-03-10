<?php 
require_once('../connection/db.php');


$sql="SELECT * FROM `tbl_size_categories` WHERE `status`='1'";
$result=$conn->query($sql);
$array = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($array, array( "id" => $row['idtbl_size_categories'], "category" => $row['name']));
}

print(json_encode($array));

?>