<?php
session_start();
require_once('../connection/db.php');

$userID=$_SESSION['userid'];


if(!isset($_POST['searchTerm'])){ 
    $sql="SELECT `product_name`, `idtbl_product`, `product_code` FROM `tbl_product` WHERE `status`=1 ORDER BY `idtbl_product` ASC LIMIT 5";
}else{
    $search = $_POST['searchTerm'];   
    $sql="SELECT `product_name`, `idtbl_product`, `product_code` FROM `tbl_product` WHERE `status`=1 AND (`product_name` LIKE '%$search%' OR `product_code` LIKE '%$search%') ORDER BY `product_name` ASC LIMIT 10";
}
$result=$conn->query($sql);
$arraylist=array();


while($row=$result->fetch_assoc()){
    $fullname = $row['product_name'] . ' - ' . $row['product_code'];
    $obj=new stdClass();
    $obj->id=$row['idtbl_product'];
    $obj->text=$fullname;
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>