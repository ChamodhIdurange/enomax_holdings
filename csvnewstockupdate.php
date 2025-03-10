<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$updatedatetime=date('Y-m-d h:i:s');
$filename = "process/newstockreport.csv";

$file1 = fopen($filename, 'r');
$file2 = fopen($filename, 'r');
$c = 0;
$x = 0;
$month = date('m');
$year = substr(date('y'), -2);;

$total = 0;
while (($line = fgetcsv($file1)) !== FALSE) {
    $c++;

    $productcode = $line[2];
    $qty = $line[4];
    $unitprice = $line[6];

    if($c == 1){
        continue;
    }
    $unitprice = str_replace(',', '', $unitprice);
    $total = $total + ($qty * $unitprice);

}

$insretorder="INSERT INTO `tbl_porder`(`potype`, `orderdate`, `subtotal`, `disamount`, `discount`, `nettotal`, `payfullhalf`, `remark`, `confirmstatus`, `dispatchissue`, `grnissuestatus`, `paystatus`, `shipstatus`, `deliverystatus`, `trackingno`, `trackingwebsite`, `callstatus`, `narration`, `cancelreason`, `returnstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('0','2022/11/03','$total','0','0','$total','0','XML Update','1','0','1','1','1','1','','-','0','-','-','0','1','$updatedatetime','$userID')";
$conn->query($insretorder);
$porderid=$conn->insert_id;

$insertgrn="INSERT INTO `tbl_grn`(`date`, `total`, `invoicenum`, `dispatchnum`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `confirm_status`) VALUES ('2022/11/03','$total','XML Update','XML Update','1','$updatedatetime','$userID', '1')";
$conn->query($insertgrn);
$grnid=$conn->insert_id;

$insertpordergrn="INSERT INTO `tbl_porder_grn`(`tbl_grn_idtbl_grn`, `tbl_porder_idtbl_porder`) VALUES ('$grnid','$porderid')";
$conn->query($insertpordergrn);

$batchNo = $year.$month.sprintf('%04s', $grnid);

while (($line = fgetcsv($file2)) !== FALSE) {
    $x++;
    $productcode = $line[2];
    $qty = $line[4];
    $unitprice = $line[5];
    $productid = $line[0];
    $saleprice = $line[6];

    if($x == 1){
        continue;
    }

    // $sqlproduct = "SELECT `idtbl_product`, `saleprice` FROM `tbl_product` WHERE `product_code` = '$productcode'";
    // $resultproduct = $conn->query($sqlproduct);
    // $row = $resultproduct->fetch_assoc();
    // $productid = $row['idtbl_product'];
    // $saleprice = $row['saleprice'];

    $saleprice = str_replace(',', '', $saleprice);
    $unitprice = str_replace(',', '', $unitprice);
    $qty = str_replace(',', '', $qty);

    $insertorderdetail="INSERT INTO `tbl_porder_detail`(`type`, `qty`, `freeqty`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`) VALUES ('0','$qty','0','$unitprice','$saleprice','1','$updatedatetime','$userID','$porderid','$productid')";
    $conn->query($insertorderdetail);

    $insretgrndetail="INSERT INTO `tbl_grndetail`(`date`, `type`, `qty`, `unitprice`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_grn_idtbl_grn`, `tbl_product_idtbl_product`) VALUES ('2022/11/03','0','$qty','$unitprice','$total','1','$updatedatetime','$userID','$grnid','$productid')";
    $conn->query($insretgrndetail);

    $insertstock="INSERT INTO `tbl_stock` (`batchqty`, `qty`, `update`, `status`, `batchno`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$qty', '$qty', '$updatedatetime', '1', '$batchNo', '$updatedatetime', '$userID', '$productid')";
    $conn->query($insertstock);
}

$insertpayment = "INSERT INTO `tbl_porder_payment`(`date`, `ordertotal`, `previousbill`, `balancetotal`, `accountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`) VALUES ('2022/11/03','$total','0','0','0','1','$updatedatetime','$userID','$porderid')";
$conn->query($insertpayment);

print_r($x);

?>