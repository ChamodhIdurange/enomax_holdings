<?php
require_once('../connection/db.php');

$productID=$_POST['productID'];
$qty=$_POST['qty'];

$sqlproduct="SELECT `tbl_product_free`.`freecount`, `tbl_product_free`.`qtycount`, `tbl_product_free`.`freeproductid`, `tbl_product`.`product_name` FROM `tbl_product_free` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_product_free`.`freeproductid` WHERE `tbl_product_free`.`status`=1 AND `tbl_product_free`.`issueproductid`='$productID' AND `tbl_product_free`.`qtycount`<='$qty'";
$resultproduct=$conn->query($sqlproduct);
$rowproduct=$resultproduct->fetch_assoc();

if($resultproduct-> num_rows > 0) {
    $calfree=floor(($qty/$rowproduct['qtycount'])*$rowproduct['freecount']);

    $obj=new stdClass();
    $obj->freecount=$calfree;
    $obj->productid=$rowproduct['freeproductid'];
    $obj->productname=$rowproduct['product_name'];
}
else{
    $obj=new stdClass();
    $obj->freecount='0';
    $obj->productid='';
    $obj->productname='';
}
echo json_encode($obj);