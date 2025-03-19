<?php
require_once('../connection/db.php');

$productID=$_POST['productID'];
$customerID=$_POST['customerID'];
$customerType=$_POST['customerType'];


$sqlproduct="SELECT `p`.`saleprice`, `p`.`unitprice`, `p`.`retail`, `s`.`suppliername`, `m`.`name` FROM `tbl_product` AS `p` LEFT JOIN `tbl_supplier` AS `s` ON (`p`.`tbl_supplier_idtbl_supplier` = `s`.`idtbl_supplier`)  LEFT JOIN `tbl_sizes` AS `m` ON (`p`.`tbl_sizes_idtbl_sizes` = `m`.`idtbl_sizes`) WHERE `p`.`idtbl_product`='$productID'";
$resultproduct=$conn->query($sqlproduct);
$rowproduct=$resultproduct->fetch_assoc();

$sqlholdstock="SELECT SUM(`qty`) as `qty` FROM `tbl_customer_order_hold_stock` WHERE `tbl_product_idtbl_product`='$productID' AND `status` = '1' AND `invoiceissue` = '0' GROUP BY `tbl_product_idtbl_product`";
$result=$conn->query($sqlholdstock);
$row=$result->fetch_assoc();

$holdqty = $row['qty'];

$sqlstock="SELECT SUM(`qty`) as `qty` FROM `tbl_stock` WHERE `tbl_product_idtbl_product`='$productID' GROUP BY `tbl_product_idtbl_product`";
$result=$conn->query($sqlstock);
$row=$result->fetch_assoc();
$stockqty = $row['qty'];

if($resultproduct-> num_rows > 0) {
    $obj=new stdClass();
    if($customerType == 1){
        $obj->saleprice=$rowproduct['retail'];
    }else{
        $obj->saleprice=$rowproduct['saleprice'];
    }
    $obj->unitprice=$rowproduct['unitprice'];
    $obj->suppliername=$rowproduct['suppliername'];
    $obj->commonname=$rowproduct['name'];
    $obj->holdqty = $holdqty ?? 0;
    $obj->availableqty = $stockqty ?? 0;
}
else{
    $obj=new stdClass();
    $obj->saleprice='0';
    $obj->unitprice='0';
    $obj->holdqty='0';
    $obj->availableqty='0';
    $obj->suppliername='';
    $obj->commonname='';
    echo 'qweqwewq';

}

echo json_encode($obj);
?>