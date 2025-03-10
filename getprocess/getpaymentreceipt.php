
<?php 
require_once('../connection/db.php');

$paymentinoiceID=$_POST['paymentinoiceID'];

$sqlpaymentdetail="SELECT * FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$resultpaymentdetail=$conn->query($sqlpaymentdetail);

$sqlpaymentmethodscash="SELECT SUM(`i`.`amount`) as `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` as `p` JOIN `tbl_invoice_payment` as `pi` on (`pi`.`idtbl_invoice_payment` = `p`.`tbl_invoice_payment_idtbl_invoice_payment`) JOIN `tbl_invoice_payment_detail` AS `i` ON (`i`.`tbl_invoice_payment_idtbl_invoice_payment` = `pi`.`idtbl_invoice_payment`) WHERE `pi`.`idtbl_invoice_payment`='$paymentinoiceID' AND `i`.`method` = '1'";
$resultpaymentmethodcash=$conn->query($sqlpaymentmethodscash);
$rowcash=$resultpaymentmethodcash->fetch_assoc();
$cashamount = $rowcash['payamount'];

$sqlpaymentmethodscheque="SELECT SUM(`i`.`amount`) as `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` as `p` JOIN `tbl_invoice_payment` as `pi` on (`pi`.`idtbl_invoice_payment` = `p`.`tbl_invoice_payment_idtbl_invoice_payment`) JOIN `tbl_invoice_payment_detail` AS `i` ON (`i`.`tbl_invoice_payment_idtbl_invoice_payment` = `pi`.`idtbl_invoice_payment`) WHERE `pi`.`idtbl_invoice_payment`='$paymentinoiceID' AND `i`.`method` = '2'";
$resultpaymentmethodcheque=$conn->query($sqlpaymentmethodscheque);
$rowcheque=$resultpaymentmethodcheque->fetch_assoc();
$chequeamount = $rowcheque['payamount'];

$sqlpayment="SELECT * FROM `tbl_invoice_payment` WHERE `idtbl_invoice_payment`='$paymentinoiceID' AND `status`=1";
$resultpayment=$conn->query($sqlpayment);
$rowpayment=$resultpayment->fetch_assoc();

$sqlpaymentbank="SELECT * FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$resultpaymentbank=$conn->query($sqlpaymentbank);
?>
<table style="width: 100%;">
    <tr>
        <td style="vertical-align: bottom;">Receipt: PR-<?php echo $paymentinoiceID; ?></td>
        <td style="text-align: right;">
            <span style="background: #000;color: #FFF;padding: 5px 15px 5px 15px;border-radius: 5px;">PAYMENT RECEIPT</span>
            <h5 style="margin-top: 10px;margin-bottom: 0px;">ENOMAX Holdings (PVT) LTD Test</h5>
            No.46, Garden City<br>
            Minuwangoda Road, Ja-ela.<br>
            Tel: 011-3468568<br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr style="border-color: #000;">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table class="table table-striped table-bordered table-black table-sm small bg-transparent tableprint">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th class="text-right">Invoice Amount</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;while($rowpaymentdetails=$resultpaymentdetail->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo 'INV-'.$rowpaymentdetails['tbl_invoice_idtbl_invoice']; ?></td>
                        <td class="text-right">
                            <?php $invoiceID=$rowpaymentdetails['tbl_invoice_idtbl_invoice']; $sqlinvoice="SELECT `total` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invoiceID' AND `status`=1"; $resultinvoice=$conn->query($sqlinvoice); $rowinvoice=$resultinvoice->fetch_assoc(); echo number_format($rowinvoice['total'], 2); ?>
                        </td>
                        <td class="text-right"><?php  echo number_format($rowpaymentdetails['discount'],2); ?></td>
                        <td class="text-right"><?php  $paymentdone = $rowinvoice['total'] - $rowpaymentdetails['discount']; echo number_format($paymentdone,2); ?></td>
                    </tr>
                    <?php $i++;} ?>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td width="50%" style="vertical-align: top;">
            <table class="table table-striped table-bordered table-black table-sm small bg-transparent tableprint">
                <thead>
                    <tr>
                        <th class="text-right">Method</th>
                        <th class="text-right">Payment</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php while($rowpaymentbank=$resultpaymentbank->fetch_assoc()){  ?>
                    <tr>
                        <td><?php if($rowpaymentbank['method']==1){echo 'Cash';}else if($rowpaymentbank['method']==2){echo 'Cheque';}else if($rowpaymentbank['method']==3){echo 'Credit Note';} ?></td>
                        <td style="text-align: right"><?php echo number_format($rowpaymentbank['amount'], 2) ?></td>
                    </tr>
                    <?php } ?>
                <tbody>
            </table>
        </td>
        <td style="vertical-align: top;">
            <table width="100%">
                <tr>
                    <td width="73%" style="text-align: right">Net Total</td>
                    <td style="text-align: right"><?php echo 'Rs.'.number_format($rowpayment['payment'], 2); ?></td>
                </tr>
                <tr>
                    <td width="73%" style="text-align: right">Payment</td>
                    <td style="text-align: right"><?php echo 'Rs.'.number_format($rowpayment['payment'], 2); ?></td>
                </tr>
                <tr>
                    <td width="73%" style="text-align: right">Balance</td>
                    <td style="text-align: right"><?php echo 'Rs.'.number_format($rowpayment['balance'], 2); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>