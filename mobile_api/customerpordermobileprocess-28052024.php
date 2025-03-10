<?php 
session_start();
require_once('../connection/db.php');//die('bc');
$userID=$_POST['userID'];

$orderdate=$_POST['orderdate'];
$remark=$_POST['remark'];
$discountpresentage=$_POST['discountpresentage'];
$total=$_POST['total'];
$discount=$_POST['discount'];
$nettotal=$_POST['nettotal'];
$repname=$_POST['repname'];
$area=$_POST['area'];
$customer=$_POST['customer'];
$paymentoption=1;
$tableData=$_POST['tableData'];
$tableData = json_decode($tableData);

$updatedatetime=date('Y-m-d h:i:s');


$month=date('n');


$insretorder="INSERT INTO `tbl_porder`(`potype`, `orderdate`, `subtotal`, `disamount`, `discount`, `nettotal`, `payfullhalf`, `remark`, `confirmstatus`, `dispatchissue`, `grnissuestatus`, `paystatus`, `shipstatus`, `deliverystatus`, `trackingno`, `trackingwebsite`, `callstatus`, `narration`, `cancelreason`, `returnstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `podiscount`, `po_amount`) VALUES ('1','$orderdate','$total','$discount','$discountpresentage','$nettotal','$paymentoption','$remark','0','0','0','0','0','0','','-','0','-','-','0','1','$updatedatetime','$userID', '0', '0')";

echo $insretorder . '//';
if($conn->query($insretorder)==true){
    $orderID=$conn->insert_id;

    $insertporderother="INSERT INTO `tbl_porder_otherinfo`(`porderid`, `mobileid`, `areaid`, `customerid`, `repid`, `status`, `updatedatetime`) VALUES ('$orderID','0','$area','$customer','$repname','1','$updatedatetime')";
    $conn->query($insertporderother);

    echo $insertporderother . '//';

    foreach ($tableData as $item) {
        $productID=$item->productID;
        $product=0;
        $unitprice=$item->unitprice;;
        $saleprice=$item->saleprice;
        $newqty=$item->newqty;
        $freeprodcutid=0;
        $freeqty=0;
        $total==$item->total;

        $insertorderdetail="INSERT INTO `tbl_porder_detail`(`type`, `qty`, `freeqty`, `freeproductid`, `unitprice`, `saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`) VALUES ('0','$newqty','$freeqty','$product','$unitprice','$saleprice','1','$updatedatetime','$userID','$orderID','$productID')";
        $conn->query($insertorderdetail);

        $updatereptarget="UPDATE `tbl_employee_target` SET `targetqtycomplete`=(`targetqtycomplete`+'$newqty') WHERE MONTH(`month`)='$month' AND `status`=1 AND `tbl_employee_idtbl_employee`='$repname' AND `tbl_product_idtbl_product`='$productID'";
        $conn->query($updatereptarget);

        $updatereptargetfree="UPDATE `tbl_employee_target` SET `targetqtycomplete`=(`targetqtycomplete`+'$freeqty') WHERE MONTH(`month`)='$month' AND `status`=1 AND `tbl_employee_idtbl_employee`='$repname' AND `tbl_product_idtbl_product`='$product'";
        $conn->query($updatereptargetfree);
    }

    echo $insertorderdetail . '//';

    echo $actionJSON='Addedd Successfully';
}
else{

    echo $actionJSON='Something went wrong';
}




