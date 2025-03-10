<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$tblData=$_POST['tblData'];
$tblPayData=$_POST['tblPayData'];
$totAmount=$_POST['totAmount'];
$payAmount=$_POST['payAmount'];
$balAmount=$_POST['balAmount'];
$paymentdate=$_POST['paymentdate'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

// mysqli_autocommit($conn, false);
// mysqli_begin_transaction($conn);

$insertpayment="INSERT INTO `tbl_invoice_payment`(`date`, `payment`, `balance`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$paymentdate','$payAmount','$balAmount','1','$updatedatetime','$userID')";
if($conn->query($insertpayment)==true){
    $paymentID=$conn->insert_id;

    foreach($tblData as $rowtabledata){
        // $invno=$rowtabledata['col_1'];
        $invoiceID=$rowtabledata['col_1'];
        $invamount=$rowtabledata['col_5'];
        $invpayamount=$rowtabledata['col_10'];
        // $fullhalfstatus=$rowtabledata['col_11'];

        if($invamount<=$invpayamount){
            $paymentcompletestatus=1;
            $fullstatus=1;
            $halfstatus=0;
        }
        else{
            $paymentcompletestatus=0;
            $fullstatus=0;
            $halfstatus=1;
        }

        if($invpayamount>0){
            $updateinvoicehas="INSERT INTO `tbl_invoice_payment_has_tbl_invoice`(`tbl_invoice_payment_idtbl_invoice_payment`, `tbl_invoice_idtbl_invoice`, `total`, `discount`, `payamount`, `fullstatus`, `halfstatus`) VALUES ('$paymentID','$invoiceID','0','0','$invpayamount','$fullstatus','$halfstatus')";
            $conn->query($updateinvoicehas);

            // if (array_key_exists($invno,$meta_array)){
            //     $invtotal=$meta_array[$invno]->invtotal;
            //     $discountamount=$meta_array[$invno]->discount;
            //     $payamount=$meta_array[$invno]->payamount;
            // }

            // $updateinvoice="UPDATE `tbl_invoice` SET `paymentcomplete`='$paymentcompletestatus', `payment_created`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invoiceID'";
            // $conn->query($updateinvoice);

            $sqlsumpayment="SELECT SUM(`payamount`) AS `netpayment` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID'";
            $resultsumpayment = $conn->query($sqlsumpayment);
            $rowsumpayment = $resultsumpayment->fetch_assoc();

            if(round($invamount, 2)<=$rowsumpayment['netpayment']){
                $paymentcompletestatus=1;
            }
            else{
                $paymentcompletestatus=0;
            }

            $updateinvoice="UPDATE `tbl_invoice` SET `paymentcomplete`='$paymentcompletestatus',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invoiceID'";
            $conn->query($updateinvoice);
        }
    }

    foreach($tblPayData as $rowtablepaydata){
        $typename=$rowtablepaydata['col_1'];
        $cashamount=$rowtablepaydata['col_2'];
        $bankamount=$rowtablepaydata['col_3'];
        $creditnote=$rowtablepaydata['col_4'];
        $chequeno=$rowtablepaydata['col_5'];
        $receiptno=$rowtablepaydata['col_6'];
        $chequedate=$rowtablepaydata['col_7'];
        $bankname=$rowtablepaydata['col_8'];
        $bankID=$rowtablepaydata['col_9'];
        $typeID=$rowtablepaydata['col_10'];
        $creditnoteID=$rowtablepaydata['col_11'];

        if($typeID==1){
            $paidamount=$cashamount;
        }
        else if($typeID==2){
            $paidamount=$bankamount;
        }
        else if($typeID==3){
            $paidamount=$creditnote;
        }

        $insertpaydetail="INSERT INTO `tbl_invoice_payment_detail`(`method`, `amount`, `branch`, `receiptno`, `chequeno`, `chequedate`, `status`, `creditnoteid`, `updatedatetime`, `tbl_bank_idtbl_bank`, `tbl_user_idtbl_user`, `tbl_invoice_payment_idtbl_invoice_payment`) VALUES ('$typeID','$paidamount','-','$receiptno','$chequeno','$chequedate','$creditnoteID','1','$updatedatetime','$bankID','$userID','$paymentID')";
        $conn->query($insertpaydetail);

        if($typeID==3){
            $updatecreditnote="UPDATE `tbl_creditenote` SET `settle`='1', `settledate`='$today' WHERE `idtbl_creditenote`='$creditnoteID'";
            $conn->query($updatecreditnote);
        }
    }

    // mysqli_commit($conn);

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    $action=json_encode($actionObj);

    $obj=new stdClass();
    $obj->paymentinvoice=$paymentID;
    $obj->action=$action;

    echo $actionJSON=json_encode($obj);
}
else{
    // mysqli_rollback($conn);

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    $action=json_encode($actionObj);

    $obj=new stdClass();
    $obj->paymentinvoice='0';
    $obj->action=$action;

    echo $actionJSON=json_encode($obj);
}

?>