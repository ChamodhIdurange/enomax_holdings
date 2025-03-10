<?php
require_once('dbConnect.php');

$refId=$_POST["refId"];
$areaid=$_POST["areaid"];


$sql="SELECT `idtbl_customer`, `name`, `address`, `tbl_area_idtbl_area` FROM `tbl_customer` WHERE `status`='1' AND `ref` = '$refId' AND `tbl_area_idtbl_area`='$areaid'";
$result = mysqli_query($con, $sql);

$lorryArray = array();
while ($row = mysqli_fetch_array($result)) {
    array_push($lorryArray, array("id" => $row['idtbl_customer'], "name" => $row['name'], "address" => $row['address'], "areaId" => $row['tbl_area_idtbl_area']));
}
echo json_encode($lorryArray);

?>