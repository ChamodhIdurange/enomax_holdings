<?php

require_once('dbConnect.php');

$sql="SELECT * FROM `tbl_product` WHERE `status`=1;";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_product'],"product_code" => $row['product_code'],"product_name" => $row['product_name'],"size" => $row['size'],"unitprice"=>$row['unitprice'],"saleprice"=>$row['saleprice'], "category" => $row['tbl_product_category_idtbl_product_category'],"groupCategory"=>$row['tbl_group_category_idtbl_group_category'],"subCategory"=>$row['tbl_sub_product_category_idtbl_sub_product_category']));
}

print(json_encode($result));
mysqli_close($con);

?>