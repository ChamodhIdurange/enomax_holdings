<?php 
require_once('../connection/db.php');

$salesmanagerid = $_POST['salesmanagerid'];

$sql="SELECT * FROM `tbl_employee` WHERE `status`='1' AND `tbl_user_type_idtbl_user_type`='7' AND `tbl_sales_manager_idtbl_sales_manager`='$salesmanagerid'";
$result=$conn->query($sql);
$array = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($array, array( "id" => $row['idtbl_employee'], "name" => $row['name']));
}

print(json_encode($array));


?>