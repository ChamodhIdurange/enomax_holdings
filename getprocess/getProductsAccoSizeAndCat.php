<?php

require_once('../connection/db.php');

$categoryId=$_POST["categoryId"];
$sizeCatId=$_POST["sizeCatId"];
$subCatId=$_POST["subCatId"];
$groupCatId=$_POST["groupCatId"];

$sql="SELECT * FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category` = '$categoryId' AND `tbl_group_category_idtbl_group_category` = '$groupCatId' AND `tbl_sub_product_category_idtbl_sub_product_category` = '$subCatId' AND `tbl_size_categories_idtbl_size_categories` = '$sizeCatId'";

$result =$conn-> query($sql); 
$arr = array();


while ($row = $result-> fetch_assoc()) { 
    array_push($arr, array( "id" => $row['idtbl_product'],"product_code" => $row['product_code'],"product_name" => $row['product_name'],"size" => $row['size'],"unitprice"=>$row['unitprice'],"saleprice"=>$row['saleprice'], "category" => $row['tbl_product_category_idtbl_product_category'],"groupCategory"=>$row['tbl_group_category_idtbl_group_category'],"subCategory"=>$row['tbl_sub_product_category_idtbl_sub_product_category'],"imagepath"=>$row['productimagepath']));
}


print(json_encode($arr));
mysqli_close($conn);

?>