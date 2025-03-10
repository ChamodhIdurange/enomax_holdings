<?php
require_once('dbConnect.php');

$userID = $_POST['refId'];
$tblData = json_decode($_POST['invoicePaymentArray']);
$tblPayData = json_decode($_POST['customerPaymentArray']);
$totAmount = $_POST['totAmount'];
$payAmount = $_POST['payAmount'];
$balAmount = $_POST['balAmount'];


$today = date('Y-m-d');
$updatedatetime = date('Y-m-d h:i:s');
$flag = true;
$con->autocommit(FALSE);

// E/invoicePaymentArray: syncPayment: [{"invoicedate":"2021-02-06","invoiceid":"1","invoicetotal":"476959","newPaymentAmount":"400000","paymentAmount":"0"},{"invoicedate":"2021-02-08","invoiceid":"7","invoicetotal":"244980","newPaymentAmount":"0","paymentAmount":"0"}]

$insertpayment = "INSERT INTO `tbl_invoice_payment`(`date`, `payment`, `balance`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$today','$payAmount','$balAmount','1','$updatedatetime','$userID')";
$resultnvoPay = mysqli_query($con, $insertpayment);
if ($resultnvoPay) {
    $paymentID = $con->insert_id;
    $paymentID;

    foreach ($tblData as $rowtabledata) {

        $invno = $rowtabledata->invoiceid;
        // $invoiceID = substr($invno, 4);

        $invamount = $rowtabledata->invoicetotal; //invoice eke gana
        $invpayamount = $rowtabledata->newPaymentAmount; //invoice ekata gewana gana
        $discount = $rowtabledata->discount;

        if ($invamount <= $invpayamount) {
            $paymentcompletestatus = 1;
            $fullstatus = 1;
            $halfstatus = 0;
        } else {
            $paymentcompletestatus = 0;
            $fullstatus = 0;
            $halfstatus = 1;
        }

        if ($invpayamount > 0) {
            $updateinvoice = "UPDATE `tbl_invoice` SET `paymentcomplete`='$paymentcompletestatus',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invno'";
            $resultnvoUpdate = mysqli_query($con, $updateinvoice);
            if (!$resultnvoUpdate) {
                $flag = false;
            }

            $updateinvoicehas = "INSERT INTO `tbl_invoice_payment_has_tbl_invoice`(`tbl_invoice_payment_idtbl_invoice_payment`, `tbl_invoice_idtbl_invoice`,`total`,`discount`,`payamount`, `fullstatus`, `halfstatus`) VALUES ('$paymentID','$invno','$invamount','$discount','$invpayamount','$fullstatus','$halfstatus')";
            $resultnvoHas = mysqli_query($con, $updateinvoicehas);

            if (!$resultnvoHas) {
                $flag = false;
            }
        }
    }

    // E/customerPaymentArray: syncPayment: [{"amount":"100000","bank":"","bankId":"","branch":"","chequedate":"","chequeno":"","method":"Cash","receiptno":""},{"amount":"300000","bank":"National Development Bank PLC","bankId":"7","branch":"knrf","chequedate":"21-02-18","chequeno":"6985","method":"Cheque","receiptno":"5865"}]

    foreach ($tblPayData as $rowtablepaydata) {

        $typename = $rowtablepaydata->method;
        $cashamount = $rowtablepaydata->amount;
        $chequeno = $rowtablepaydata->chequeno;
        $receiptno = $rowtablepaydata->receiptno;
        $chequedate = $rowtablepaydata->chequedate;
        $bankID = $rowtablepaydata->bankId;
        $branch = $rowtablepaydata->branch;
        $typeID = "";

        if ($typename == "Cash") {
            $typeID = "1";
        } else {
            $typeID = "2";
        }

        $insertpaydetail = "INSERT INTO `tbl_invoice_payment_detail`(`method`, `amount`, `branch`, `receiptno`, `chequeno`, `chequedate`, `status`, `updatedatetime`, `tbl_bank_idtbl_bank`, `tbl_user_idtbl_user`, `tbl_invoice_payment_idtbl_invoice_payment`) 
        VALUES ('$typeID','$cashamount','$branch','$receiptno','$chequeno','$chequedate','1','$updatedatetime','$bankID','$userID','$paymentID')";
        $resultnvoPayDetails = mysqli_query($con, $insertpaydetail);
        if (!$resultnvoPayDetails) {
            $flag = false;
        }
    }
} else {
    $flag = false;
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
