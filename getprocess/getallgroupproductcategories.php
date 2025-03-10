<?php 
require_once('../connection/db.php');

$producategory = $_POST['producategory'];

$sql="SELECT * FROM `tbl_group_category` WHERE `status`='1' AND `tbl_product_category_idtbl_product_category` = '$producategory'";
$result=$conn->query($sql);
$array = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($array, array( "id" => $row['idtbl_group_category'], "category" => $row['category']));
}

print(json_encode($array));

?>