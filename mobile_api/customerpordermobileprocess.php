<?php 
session_start();
require_once('../connection/db.php');//die('bc');
// Get JSON input instead of $_POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if JSON is valid
if ($data === null) {
    echo json_encode(['code' => '400', 'message' => 'Invalid JSON']);
    exit;
}
$userID = $data['userID'];
$orderdate = $data['orderdate'];
$remark = $data['remark'];
$discountpresentage = $data['discountpresentage'];
$total = $data['total'];
$discount = $data['discount'];
$nettotal = $data['nettotal'];
$repname = $data['repname'];
$area = $data['area'];
$customer = $data['customer'];
$location = $data['locationID'];
$podiscount = $data['podiscount'];
$paymentoption = 1;
$tableData = $data['tableData'];

$updatedatetime=date('Y-m-d h:i:s');

$month=date('n');

$query = "SELECT MAX(idtbl_customer_order) AS max_id FROM tbl_customer_order";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $next_id = $row['max_id'] + 1;
} else {
    $next_id = 1;
}

$dateformat = date('y/m/');
$cuspono = 'CP/'. $dateformat . $next_id;


$insretorder = "INSERT INTO `tbl_customer_order`(`cuspono`, `date`, `total`, `discount`, `podiscount`, `vat`, `nettotal`, `remark`, `vatpre`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_employee_idtbl_employee`, `tbl_locations_idtbl_locations`, `tbl_customer_idtbl_customer`) VALUES ('$cuspono', '$orderdate','$total','$discount', '$podiscount', '0', '$nettotal', '$remark', '0','1', '$updatedatetime', '$userID', '$area', '$repname', '$location' , '$customer')";

    if ($conn->query($insretorder) == true) {
        $orderID = $conn->insert_id;

        $insretoriginalorder = "INSERT INTO `tbl_original_customer_order`(`cuspono`, `date`, `total`, `discount`, `podiscount`, `vat`, `nettotal`, `remark`, `vatpre`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_employee_idtbl_employee`, `tbl_locations_idtbl_locations`, `tbl_customer_idtbl_customer`, `tbl_customer_order_idtblcustomer_order`) VALUES ('$cuspono', '$orderdate','$total','$discount', '$podiscount', '0', '$nettotal', '$remark', '0','1', '$updatedatetime', '$userID', '$area', '$repname', '$location' , '$customer', '$orderID')";
        $conn->query($insretoriginalorder);
        $originalOrderID = $conn->insert_id;

        foreach ($tableData as $item) {
            $productID = $item['productID'];
        $product = 0;
        $unitprice = $item['unitprice'];
        $saleprice = $item['saleprice'];
        $newqty = $item['newqty'];
        $total = $item['nettotal'];
        $freeprodcutid = 0;
        $freeqty = 0;

            $insertorderdetail = "INSERT INTO `tbl_customer_order_detail`(`orderqty`, `total`, `confirmqty`, `dispatchqty`, `qty`, `unitprice`, `saleprice`, `discountpresent`, `discount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_order_idtbl_customer_order`, `tbl_product_idtbl_product`) VALUES ('$newqty', '$total', '$newqty', '$newqty', '$newqty', '$unitprice','$saleprice', '0', '0', '1','$updatedatetime','$userID','$orderID','$productID')";
            $conn->query($insertorderdetail);


            $insertoriginalorderdetail = "INSERT INTO `tbl_original_customer_order_detail`(`orderqty`, `total`, `confirmqty`, `dispatchqty`, `qty`, `unitprice`, `saleprice`, `discountpresent`, `discount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_original_customer_order_idtbl_original_customer_order`, `tbl_product_idtbl_product`) VALUES ('$newqty', '$total', '$newqty', '$newqty', '$newqty', '$unitprice','$saleprice', '0', '0', '1','$updatedatetime','$userID','$originalOrderID','$productID')";
            $conn->query($insertoriginalorderdetail);

            $insertholdstock = "INSERT INTO `tbl_customer_order_hold_stock`(`qty`, `invoiceissue`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_order_idtbl_customer_order`) VALUES ('$newqty', '0', '1', '$updatedatetime', '$userID', '$productID','$orderID')";
            $conn->query($insertholdstock);
        }

        $actionObj=new stdClass();
        $actionObj->code='200';
        $actionObj->message='Addedd Successfully';
    
        echo json_encode($actionObj);
    } else {
        echo $actionJSON='Something went wrong';

    }
