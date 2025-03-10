<?php 
require_once('../connection/db.php');

$employeeId=$_POST['employeeId'];

$sql="SELECT * FROM `tbl_customer` WHERE `status`='1' AND `ref`='$employeeId'";
$result = mysqli_query($conn, $sql);

$customerarray = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($customerarray, array("id" => $row['idtbl_customer'], "name" => $row['name'], "nic" => $row['nic'], "phone" => $row['phone'], "email" => $row['email'], "address" => $row['address'], "areaId" => $row['tbl_area_idtbl_area']));
}

echo json_encode($customerarray);
?>
