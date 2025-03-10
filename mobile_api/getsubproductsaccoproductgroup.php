<?php 
require_once('../connection/db.php');

$producategory = $_POST['producategory'];
$groupcategory = $_POST['groupcategory'];

$sql="SELECT DISTINCT(`s`.`idtbl_sub_product_category`), `s`.`category` FROM `tbl_sub_product_category` AS `s` LEFT JOIN `tbl_product` AS `p` ON (`s`.`idtbl_sub_product_category` = `p`.`tbl_sub_product_category_idtbl_sub_product_category`) WHERE `s`.`status`='1' AND `s`.`tbl_product_category_idtbl_product_category` = '$producategory' AND `p`.`tbl_group_category_idtbl_group_category` = '$groupcategory'";
$result=$conn->query($sql);
$array = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($array, array( "id" => $row['idtbl_sub_product_category'], "category" => $row['category']));
}

print(json_encode($array));

?>