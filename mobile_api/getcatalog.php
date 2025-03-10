<?php

require_once('dbConnect.php');

$sql="SELECT `tbl_catalog`.`idtbl_catalog`, `tbl_catalog_category`.`category` FROM `tbl_catalog` LEFT JOIN `tbl_catalog_category` ON (`tbl_catalog_category`.`idtbl_catalog_category` = `tbl_catalog`.`tbl_catalog_category_idtbl_catalog_category`)  WHERE `tbl_catalog`.`status`='1'";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_catalog'], "category" => $row['category']));
}
header('Content-Type: application/json');
print(json_encode($result));
mysqli_close($con);

?>