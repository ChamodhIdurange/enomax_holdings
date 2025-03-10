<?php session_start();


require_once('../connection/db.php'); //die('bc');
$userID = $_SESSION['userid'];

$updatedatetime = date('Y-m-d h:i:s');

$tableData = $_POST['tableData'];

$returntype = $_POST['returntype'];
$customerinvoice = $_POST['customerinvoice'];
$total = $_POST['total'];
$invoicestatus = $_POST['invoicestatus'];
$repId = $_POST['repId'];

$today = date('Y-m-d');

if ($returntype == 3 || $returntype == 1) {
    $customer = $_POST['customer'];
    $remarks = $_POST['remarks'];

    $query = "INSERT INTO `tbl_return`(`returntype`, `has_invoice`, `returndate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `acceptance_status`, `total`, `damaged_reason`, `credit_note`, `credit_note_issue`, `tbl_invoice_idtbl_invoice`, `tbl_customer_idtbl_customer`, `tbl_employee_idtbl_employee`) VALUES ('$returntype', '$invoicestatus', '$today','1','$updatedatetime','$userID', '0', '$total', '$remarks', '0', '0', '$customerinvoice', '$customer', '$repId')";

    if ($conn->query($query) == true) {
        $last_id = mysqli_insert_id($conn);

        foreach ($tableData as $rowtabledata) {
            $productID = $rowtabledata['col_1'];
            $qty = $rowtabledata['col_4'];
            $discount = $rowtabledata['col_5'];
            $subtotal = $rowtabledata['col_6'];
            $unitprice = $rowtabledata['col_11'];


            $insertreturndetails = "INSERT INTO `tbl_return_details`(`unitprice`, `qty`, `actualqty`, `discount`, `total`, `tbl_product_idtbl_product`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_return_idtbl_return`) VALUES ('$unitprice','$qty', '0', '$discount', '$subtotal', '$productID','$updatedatetime','$userID','$last_id')";
            $conn->query($insertreturndetails);
        }
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Add Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';

        echo $actionJSON = json_encode($actionObj);
    } else {
        header("Location:../productreturn.php?action=5");
    }
} else if ($returntype == 2) {
    $supplier = $_POST['supplier'];


    $query = "INSERT INTO `tbl_return`(`returntype`, `returndate`, `has_invoice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `acceptance_status`, `total`, `damaged_reason`, `credit_note`, `credit_note_issue`, `tbl_invoice_idtbl_invoice`, `tbl_return_idtbl_return`) VALUES ('$returntype', '$invoicestatus', '$today','1','$updatedatetime','$userID', '0', '$total', '$remarks', '0', '0', '$customerinvoice', '$customer')";

    echo $query;

    if ($conn->query($query) == true) {
        $last_id = mysqli_insert_id($conn);

        foreach ($tableData as $rowtabledata) {
            $productID = $rowtabledata['col_1'];
            $qty = $rowtabledata['col_4'];
            $discount = $rowtabledata['col_5'];
            $subtotal = $rowtabledata['col_6'];
            $unitprice = $rowtabledata['col_11'];


            $insertreturndetails = "INSERT INTO `tbl_return_details`(`unitprice`, `qty`, `actualqty`, `discount`, `total`, `tbl_product_idtbl_product`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_return_idtbl_return`) VALUES ('$unitprice','$qty', '0', '$discount', '$subtotal', '$productID','$updatedatetime','$userID','$last_id')";
            $conn->query($insertreturndetails);
        }
        header("Location:../productreturn.php?action=4");
    } else {
        header("Location:../productreturn.php?action=5");
    }
}
