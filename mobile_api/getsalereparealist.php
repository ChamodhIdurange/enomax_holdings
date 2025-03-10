<?php
require_once('dbConnect.php');

$refId=$_POST["refId"];


$sql="SELECT `tbl_area`.`idtbl_area`, `tbl_area`.`area` FROM `tbl_area` LEFT JOIN `tbl_employee_area` ON `tbl_employee_area`.`tbl_area_idtbl_area`=`tbl_area`.`idtbl_area` WHERE `tbl_employee_area`.`tbl_employee_idtbl_employee`='$refId'";
$result = mysqli_query($con, $sql);

$lorryArray = array();
while ($row = mysqli_fetch_array($result)) {
    array_push($lorryArray, array("id" => $row['idtbl_area'], "area" => $row['area']));
}
echo json_encode($lorryArray);

?>