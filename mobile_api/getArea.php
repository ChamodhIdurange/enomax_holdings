<?php
require_once('dbConnect.php');

$sql="SELECT `idtbl_area`, `area`, `status`, `updatedatetime`, `tbl_user_idtbl_user` FROM `tbl_area` WHERE `status`='1'";
$result = mysqli_query($con, $sql);

$lorryArray = array();
while ($row = mysqli_fetch_array($result)) {
    array_push($lorryArray, array("id" => $row['idtbl_area'], "area" => $row['area']));
}
echo json_encode($lorryArray);

?>