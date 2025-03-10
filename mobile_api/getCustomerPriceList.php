<?php
require_once('dbConnect.php');

$sql = "SELECT `idtbl_customer_product`,`saleprice`, `tbl_product_idtbl_product`, `tbl_customer_idtbl_customer` FROM `tbl_customer_product` WHERE `status`=1";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
   
    array_push($result, array("id"=>$row['idtbl_customer_product'],"saleprice" => $row['saleprice'], "productId" => $row['tbl_product_idtbl_product'], "customerId" => $row['tbl_customer_idtbl_customer']));
}

print(json_encode($result));
mysqli_close($con);
?>
