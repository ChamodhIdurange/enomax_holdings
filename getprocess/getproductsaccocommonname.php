<?php
require_once('../connection/db.php');

session_start();
$productcommonname=$_POST['productcommonname'];

$array = [];

$sql="SELECT `product_name`, `idtbl_product` FROM `tbl_product` WHERE `common_name` = '$productcommonname' AND `status` = '1'";
$result =$conn-> query($sql); 


while ($row = $result-> fetch_assoc()) { 
    $obj=new stdClass();
    $obj->id=$row['idtbl_product'];
    $obj->name=$row['product_name'];
    array_push($array,$obj);
}


echo json_encode($array);
?>