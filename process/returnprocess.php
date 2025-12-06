<?php
session_start();
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d H:i:s');
$today = date('Y-m-d');

$tableData = $_POST['tableData'];
$returntype = $_POST['returntype'];
$customerinvoice = $_POST['customerinvoice'];
$total = $_POST['total'];
$invoicestatus = $_POST['invoicestatus'];
$repId = $_POST['repId'];

if ($returntype == 3 || $returntype == 1) {
    // Customer return or Damage return
    $customer = $_POST['customer'];
    $remarks = $_POST['remarks'];

    $query = "INSERT INTO `tbl_return`(
        `returntype`, `has_invoice`, `returndate`, `status`, `updatedatetime`, 
        `tbl_user_idtbl_user`, `acceptance_status`, `total`, `damaged_reason`, 
        `credit_note`, `credit_note_issue`, `tbl_invoice_idtbl_invoice`, 
        `tbl_customer_idtbl_customer`, `tbl_employee_idtbl_employee`
    ) VALUES (
        '$returntype', '$invoicestatus', '$today', '1', '$updatedatetime',
        '$userID', '0', '$total', '$remarks', '0', '0', '$customerinvoice', 
        '$customer', '$repId'
    )";

    if ($conn->query($query) == true) {
        $last_id = mysqli_insert_id($conn);

        foreach ($tableData as $rowtabledata) {
            $productID = $rowtabledata['col_1'];
            $qty = $rowtabledata['col_4'];
            $discount = $rowtabledata['col_5'];
            $subtotal = $rowtabledata['col_6'];
            $unitprice = $rowtabledata['col_11'];

            $insertreturndetails = "INSERT INTO `tbl_return_details`(
                `unitprice`, `qty`, `actualqty`, `discount`, `total`, 
                `tbl_product_idtbl_product`, `updatedatetime`, `tbl_user_idtbl_user`, 
                `tbl_return_idtbl_return`
            ) VALUES (
                '$unitprice', '$qty', '0', '$discount', '$subtotal', '$productID',
                '$updatedatetime', '$userID', '$last_id'
            )";
            $conn->query($insertreturndetails);
        }
        
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = 'Success';
        $actionObj->message = 'Return Added Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';
        echo json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-circle';
        $actionObj->title = 'Error';
        $actionObj->message = 'Failed to insert return: ' . $conn->error;
        $actionObj->type = 'error';
        echo json_encode($actionObj);
    }
    
} else if ($returntype == 2) {
    // Supplier return
    $supplier = $_POST['supplier'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $query = "INSERT INTO `tbl_return` (
        `returntype`, `has_invoice`, `returndate`, `status`, `updatedatetime`, 
        `tbl_user_idtbl_user`, `acceptance_status`, `total`, `damaged_reason`, 
        `credit_note`, `credit_note_issue`, `tbl_customer_idtbl_customer`,
        `tbl_employee_idtbl_employee`, `tbl_invoice_idtbl_invoice`, `tbl_supplier_idtbl_supplier`
    ) VALUES (
        '$returntype', '$invoicestatus', '$today', '1', '$updatedatetime',
        '$userID', '0', '$total', '$remarks', '0', '0', '0', '0', '0', '$supplier'
    )";

    if ($conn->query($query) === true) {
        $last_id = mysqli_insert_id($conn);

        foreach ($tableData as $rowtabledata) {
            $productID = $rowtabledata['col_1'];
            $qty = $rowtabledata['col_4'];
            $discount = $rowtabledata['col_5'];
            $subtotal = $rowtabledata['col_6'];
            $unitprice = $rowtabledata['col_11'];
            $stockId = $rowtabledata['col_12'];  // GET STOCK ID
            $batchQty = $rowtabledata['col_13'];  // GET BATCH NUMBER

            // Insert return details
            $insertreturndetails = "INSERT INTO `tbl_return_details` (
                `unitprice`, `qty`, `actualqty`, `discount`, `total`, 
                `tbl_product_idtbl_product`, `updatedatetime`, `tbl_user_idtbl_user`, 
                `tbl_return_idtbl_return`
            ) VALUES (
                '$unitprice', '$qty', '0', '$discount', '$subtotal', '$productID',
                '$updatedatetime', '$userID', '$last_id'
            )";

            if (!$conn->query($insertreturndetails)) {
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-exclamation-circle';
                $actionObj->title = 'Error';
                $actionObj->message = 'Failed to insert return details: ' . $conn->error;
                $actionObj->type = 'error';
                echo json_encode($actionObj);
                exit;
            }

            // Update ONLY the specific stock row using stock ID
            if ($stockId && $stockId != '0') {
                $updateStock = "UPDATE `tbl_stock` 
                                SET `qty` = `qty` - '$qty',
                                    `updatedatetime` = '$updatedatetime',
                                    `tbl_user_idtbl_user` = '$userID'
                                WHERE `idtbl_stock` = '$stockId'";

                if (!$conn->query($updateStock)) {
                    $actionObj = new stdClass();
                    $actionObj->icon = 'fas fa-exclamation-circle';
                    $actionObj->title = 'Error';
                    $actionObj->message = 'Failed to update stock: ' . $conn->error;
                    $actionObj->type = 'error';
                    echo json_encode($actionObj);
                    exit;
                }

                // Check if stock goes negative for this specific batch
                $checkStock = "SELECT `qty`, `batchqty` FROM `tbl_stock` 
                               WHERE `idtbl_stock` = '$stockId'";
                $result = $conn->query($checkStock);
                if ($result && $row = $result->fetch_assoc()) {
                    if ($row['qty'] < 0) {
                        // Rollback the stock update
                        $rollbackStock = "UPDATE `tbl_stock` 
                                          SET `qty` = `qty` + '$qty'
                                          WHERE `idtbl_stock` = '$stockId'";
                        $conn->query($rollbackStock);
                        
                        $actionObj = new stdClass();
                        $actionObj->icon = 'fas fa-exclamation-circle';
                        $actionObj->title = 'Error';
                        $actionObj->message = 'Insufficient stock in batch: ' . $row['batchqty'];
                        $actionObj->type = 'error';
                        echo json_encode($actionObj);
                        exit;
                    }
                }
            } else {
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-exclamation-circle';
                $actionObj->title = 'Error';
                $actionObj->message = 'Stock ID missing for product ID: ' . $productID;
                $actionObj->type = 'error';
                echo json_encode($actionObj);
                exit;
            }
        }

        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = 'Success';
        $actionObj->message = 'Supplier Return Added Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';
        echo json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-circle';
        $actionObj->title = 'Error';
        $actionObj->message = 'Failed to insert supplier return: ' . $conn->error;
        $actionObj->type = 'error';
        echo json_encode($actionObj);
    }
}
?>