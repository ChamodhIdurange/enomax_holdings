<?php

require_once('dbConnect.php');

$repid=$_POST['repid'];
$salesmanagerid=$_POST['salesmanagerid'];

if($repid != 0){
    $sql="SELECT `tbl_customer_order`.`idtbl_customer_order` as 'orderid', `tbl_customer_order`.`date`, `tbl_customer_order`.`status`, `tbl_customer_order`.`nettotal`, `tbl_customer_order`.`remark`, `tbl_customer`.`name` as 'customername', `tbl_area`.`area`, `tbl_customer`.`address` FROM `tbl_customer_order` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer_order`.`tbl_area_idtbl_area` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_customer_order`.`tbl_customer_idtbl_customer` WHERE `tbl_customer_order`.`status`=1 AND `tbl_customer_order`.`tbl_employee_idtbl_employee`='$repid'";
}

if($salesmanagerid != 0){
    $sql="SELECT `tbl_customer_order`.`idtbl_customer_order` as 'orderid', `tbl_customer_order`.`date`, `tbl_customer_order`.`status`, `tbl_customer_order`.`nettotal`, `tbl_customer_order`.`remark`, `tbl_customer`.`name`  as 'customername', `tbl_area`.`area`, `tbl_customer`.`address` FROM `tbl_customer_order` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer_order`.`tbl_area_idtbl_area` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_customer_order`.`tbl_employee_idtbl_employee` LEFT JOIN `tbl_sales_manager` ON `tbl_sales_manager`.`idtbl_sales_manager`=`tbl_employee`.`tbl_sales_manager_idtbl_sales_manager` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_customer_order`.`tbl_customer_idtbl_customer` WHERE `tbl_customer_order`.`status`=1 AND `tbl_sales_manager`.`idtbl_sales_manager`='$salesmanagerid'";
}


$result = mysqli_query($con, $sql);
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orderID=$row['orderid'];

        $datasub=array();
        $sqldetail="SELECT `tbl_customer_order_detail`.`orderqty`, `tbl_customer_order_detail`.`saleprice`, `tbl_customer_order_detail`.`saleprice`, `tbl_customer_order_detail`.`total`, `tbl_customer_order_detail`.`discount`, `tbl_product`.`product_name`, (tbl_customer_order_detail.orderqty * tbl_customer_order_detail.saleprice) AS calctotal FROM `tbl_customer_order_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_customer_order_detail`.`tbl_product_idtbl_product` WHERE `tbl_customer_order_detail`.`status`=1 AND `tbl_customer_order_detail`.`tbl_customer_order_idtbl_customer_order`='$orderID'";
        $resultdetail = mysqli_query($con, $sqldetail);

        while($rowdetail = $resultdetail->fetch_assoc()) {
            $datasub[]=$rowdetail;
        }

        $obj=new stdClass();
        $obj->data=$row;
        $obj->detaildata=$datasub;


        array_push($data, $obj);

        // $data[] = $obj;
    }
} else {
    echo "0 results";
}

echo json_encode($data);

mysqli_close($con);

