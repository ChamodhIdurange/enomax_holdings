<?php

require_once('dbConnect.php');

$repid=$_POST['repid'];

$sql="SELECT * FROM `tbl_porder` LEFT JOIN `tbl_porder_otherinfo` ON `tbl_porder_otherinfo`.`porderid`=`tbl_porder`.`idtbl_porder` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_porder_otherinfo`.`customerid` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_porder_otherinfo`.`areaid` WHERE `tbl_porder`.`status`=1 AND `tbl_porder_otherinfo`.`repid`='$repid'";

$result = mysqli_query($con, $sql);
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orderID=$row['idtbl_porder'];

        $datasub=array();
        $sqldetail="SELECT * FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
        $resultdetail = mysqli_query($con, $sqldetail);
        while($rowdetail = $resultdetail->fetch_assoc()) {
            $datasub[]=$rowdetail;
        }
        // print_r($rowdetail);

        $obj=new stdClass();
        $obj->data=$row;
        $obj->detaildata=$datasub;

        $data[] = $obj;
    }
} else {
    echo "0 results";
}
// while ($row = mysqli_fetch_array($res)) {
//     array_push($result, array( "id" => $row['idtbl_product_image'], "path" => $row['imagepath'], "catalog_id" => $row['tbl_catalog_idtbl_catalog'],  "category" => $row['category']));
// }

echo json_encode($data);
mysqli_close($con);

