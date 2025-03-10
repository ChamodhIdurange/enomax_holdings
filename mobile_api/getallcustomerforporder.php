<?php

require_once('dbConnect.php');

$sql="SELECT * FROM `tbl_customer`  WHERE `status`='1' ";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_customer'], "customername" => $row['name']));
}

print(json_encode($result));
mysqli_close($con);

?>