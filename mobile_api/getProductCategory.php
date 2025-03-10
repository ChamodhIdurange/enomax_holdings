<?php

require_once('dbConnect.php');

$sql="SELECT * FROM `tbl_product_category`  WHERE `status`='1' ";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_product_category'], "category" => $row['category']));
}

print(json_encode($result));
mysqli_close($con);

?>