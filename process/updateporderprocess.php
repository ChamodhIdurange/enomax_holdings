<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$orderDetails = $_POST['orderDetails'];
$porderId = $_POST['porderId'];
$nettotal = $_POST['nettotal'];
$updatedatetime = date('Y-m-d h:i:s');

// Insert order
$insertOrderQuery = "UPDATE `tbl_porder` SET `total`='$nettotal', `nettotal`='$nettotal' WHERE `idtbl_porder`='$porderId'";
// echo $insertOrderQuery;

if ($conn->query($insertOrderQuery) === TRUE) {
    $orderID = $conn->insert_id;

    foreach ($orderDetails as $detail) {
        $productId = $detail['productId'];
        $unitPrice = $detail['unitPrice'];
        $saleprice = $detail['saleprice'];
        $retail = $detail['retailprice'];
        $recordOption = $detail['recordOption'];
        $detailsId = $detail['detailsId'];

        $newQty = str_replace(',', '', $detail['newQty']);
        $newQty = trim($newQty); 
        $newQty = is_numeric($newQty) ? (float)$newQty : 0; 

        $newPrice = ($newQty !== "" && $newQty !== null) ? $unitPrice : null;
        $newsalePrice = ($newQty !== "" && $newQty !== null) ? $saleprice : null;

        $sqlupdateproduct = "UPDATE `tbl_product` SET `unitprice`='$unitPrice', `saleprice`='$saleprice', `retail`='$retail' WHERE `idtbl_product` = '$productId'";
        $conn->query($sqlupdateproduct);

        if ($newPrice !== null) {
            $totalPrice = $newQty * $unitPrice; 

            if($recordOption == 0){
                $insertUpdateQuery = "INSERT INTO tbl_porder_detail (`qty`, `unitprice`, `saleprice`, `total`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_porder_idtbl_porder`) VALUES ('$newQty', '$newPrice', '$newsalePrice', '$totalPrice', '1', '$updatedatetime', '$userID', '$productId', '$porderId')";
            } else {
                $insertUpdateQuery = "UPDATE `tbl_porder_detail` SET `qty`='$newQty', `unitprice`='$newPrice', `saleprice`='$newsalePrice', `total`='$totalPrice' WHERE `idtbl_porder_detail` = '$detailsId'";
            }

            if ($conn->query($insertUpdateQuery) !== TRUE) {
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
        'query' => $insertUpdateQuery,
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
