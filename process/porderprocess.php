<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$orderDate = $_POST['orderdate'];
$remark = $_POST['remark'];
$orderDetails = $_POST['orderDetails'];
$total = $_POST['total'];
$nettotal = $_POST['nettotal'];
$vatper = $_POST['vatper'];
$vatamount = $_POST['vatamount'];
$supplierId = $_POST['supplierId'];
$updatedatetime = date('Y-m-d h:i:s');

// Insert order
$insertOrderQuery = "INSERT INTO `tbl_porder` (`orderdate`, `total`, `vat`, `nettotal`, `vatpre`, `remark`, `confirmstatus`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_supplier_idtbl_supplier`) 
VALUES ('$orderDate', '$total', '$vatamount', '$nettotal', '$vatper', '$remark', '0', '1', '$updatedatetime', '$userID', '$supplierId')";

if ($conn->query($insertOrderQuery) === TRUE) {
    $orderID = $conn->insert_id;

    foreach ($orderDetails as $detail) {
        $productId = $detail['productId'];
        $unitPrice = $detail['unitPrice'];
        $saleprice = $detail['saleprice'];
        $retail = $detail['retailprice'];

        $newQty = str_replace(',', '', $detail['newQty']);

        $newPrice = !empty($newQty) ? $unitPrice : null;
        $newsalePrice = !empty($newQty) ? $saleprice : null;

        $sqlupdateproduct = "UPDATE `tbl_product` SET `unitprice`='$unitPrice', `saleprice`='$saleprice', `retail`='$retail' WHERE `idtbl_product` = '$productId'";
        $conn->query($sqlupdateproduct) ;

        if ($newPrice !== null) {
            $totalPrice = $newQty * $unitPrice;

            $insertDetailQuery = "INSERT INTO tbl_porder_detail (`qty`, `unitprice`, `saleprice`, `total`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_porder_idtbl_porder`) 
                                VALUES ('$newQty', '$newPrice', '$newsalePrice', '$totalPrice', '1', '$updatedatetime', '$userID', '$productId', '$orderID')";

            if ($conn->query($insertDetailQuery) !== TRUE) {
                // Handle error inserting order detail
                echo json_encode([
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => '',
                    'message' => 'Error inserting order detail',
                    'url' => '',
                    'target' => '_blank',
                    'type' => 'danger'
                ]);
                exit();
            }
        }
    }

    echo json_encode([
        'icon' => 'fas fa-check-circle',
        'title' => '',
        'message' => 'Add Successfully',
        'url' => '',
        'target' => '_blank',
        'type' => 'success'
    ]);
} else {
    // Handle error inserting order
    echo json_encode([
        'icon' => 'fas fa-exclamation-triangle',
        'title' => '',
        'message' => 'Error inserting order',
        'url' => '',
        'target' => '_blank',
        'type' => 'danger'
    ]);
}

$conn->close();
?>
