<?php
require_once('dbConnect.php');
$date = $_POST["date"];
$userId = $_POST["usrId"];
$customerId = $_POST["customerId"];
$rejectReason = $_POST["rejectReason"];
$details = $_POST["details"];

$detailsJson = json_decode($details);

$flag = true;
$con->autocommit(FALSE);

$sqlMain = "INSERT INTO `tbl_customer_ava_qty`(`date`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_reject_reason_idtbl_reject_reason`) VALUES ('$date','1',NOW(),'$userId','$customerId','$rejectReason')";
$result = mysqli_query($con, $sqlMain);
$last_id = $con->insert_id;
if (!$result) {
    $flag = false;
}

foreach ($detailsJson as $indet) {

    $customerId = $indet->customerId;
    $productId = $indet->productId;
    $shopFullQty = $indet->shopFullQty;
    $shopEmptyQty = $indet->shopEmptyQty;
    $bufferStock = $indet->bufferStock;

    $date = $indet->date;

    $sqlinsrtitm = "INSERT INTO `tbl_customer_ava_qty_detail`( `fullqty`, `emptyqty`, `bufferqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_ava_qty_idtbl_customer_ava_qty`, `tbl_product_idtbl_product`)VALUES ('$shopFullQty','$shopEmptyQty','$bufferStock','1',NOW(),'$userId','$last_id','$productId')";
    $resultItem = mysqli_query($con, $sqlinsrtitm);

    if (!$resultItem) {
        $flag = false;
    }
}

if ($flag) {
    $con->commit();
    $response = array("code" => '200', "message" => 'Update Complete');
    print_r(json_encode($response));
} else {
    $con->rollback();
    $response = array("code" => '500', "message" => 'Update Not Complete');
    print_r(json_encode($response));
}

?>
