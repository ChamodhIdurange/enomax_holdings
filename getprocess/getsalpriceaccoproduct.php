<?php
require_once('../connection/db.php');

$productID=$_POST['productID'];

$sqlproduct="SELECT `p`.`saleprice`, `p`.`unitprice`, `s`.`suppliername` FROM `tbl_product` AS `p` LEFT JOIN `tbl_supplier` AS `s` ON (`p`.`tbl_supplier_idtbl_supplier` = `s`.`idtbl_supplier`) WHERE `p`.`idtbl_product`='$productID'";
$resultproduct=$conn->query($sqlproduct);
$rowproduct=$resultproduct->fetch_assoc();

if($resultproduct-> num_rows > 0) {
    $obj=new stdClass();
    $obj->saleprice=$rowproduct['saleprice'];
    $obj->unitprice=$rowproduct['unitprice'];
    $obj->suppliername=$rowproduct['suppliername'];
}
else{
    $obj=new stdClass();
    $obj->saleprice='0';
    $obj->unitprice='0';
    $obj->suppliername='';
}
echo json_encode($obj);
?>