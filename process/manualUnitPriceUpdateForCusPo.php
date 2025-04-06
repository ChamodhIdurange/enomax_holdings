<?php
require_once('../connection/db.php');

$sql="SELECT * FROM `tbl_customer_order_detail`";
$result=$conn->query($sql);
$count = 0;
while($row=$result->fetch_assoc()){
    $count = $count + 1;
    $productId=$row['tbl_product_idtbl_product'];
    $detailId=$row['idtbl_customer_order_detail'];

    $sqlunitprice="SELECT `unitprice` FROM `tbl_product` WHERE `idtbl_product`='$productId'";
    $resultunitprice=$conn->query($sqlunitprice);
    $rowunitprice=$resultunitprice->fetch_assoc();
    $unitprice=$rowunitprice['unitprice'];

    

    $sqlupdate="UPDATE `tbl_customer_order_detail` SET `unitprice`='$unitprice' WHERE `idtbl_customer_order_detail`='$detailId'";
    $conn->query($sqlupdate);
}

echo $count;

?>