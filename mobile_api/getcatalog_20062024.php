<?php

require_once('dbConnect.php');

$sql="SELECT * FROM `tbl_catalog_category`  WHERE `status`='1' ";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_catalog_category'], "category" => $row['category']));
}
header('Content-Type: application/json');
print(json_encode($result));
mysqli_close($con);

?>