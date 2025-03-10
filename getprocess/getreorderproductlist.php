<?php
require_once('../connection/db.php');

session_start();
$locationID=$_POST['locationID'];

$array = [];


$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

// $sql2="SELECT  `g`.`idtbl_tbl_group_category` as 'groupid', `g`.`category` as 'groupname' FROM `tbl_product_category` as `p` JOIN `tbl_group_category` AS `g` ON (`p`.`idtbl_product_category` = `g`.`tbl_product_category_idtbl_product_category`) WHERE `p`.`idtbl_product_category` = '$categoryID' and `p`.`status` = '1' and `g`.`status` = '1' ";
// $result2 =$conn-> query($sql2); 



while ($row = $resultproduct-> fetch_assoc()) { 
    $obj=new stdClass();
    $obj->productname=$row['product_name'];
    $obj->productid=$row['idtbl_product'];
    array_push($array,$obj);
}


echo json_encode($array);
?>