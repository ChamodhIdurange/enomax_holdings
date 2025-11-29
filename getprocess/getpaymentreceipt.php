<?php 
require_once('../connection/db.php');

$paymentinoiceID=$_POST['paymentinoiceID'];

// Get tax rate
$taxQuery = "SELECT `rate` FROM `tbl_tax` LIMIT 1";
$taxResult = $conn->query($taxQuery);
$tax = 0;
if ($taxResult && $taxResult->num_rows > 0) {
    $taxRow = $taxResult->fetch_assoc();
    $tax = $taxRow['rate'];
}

$sqlpaymentdetail="SELECT * FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$resultpaymentdetail=$conn->query($sqlpaymentdetail);

// Check if any invoice is a VAT customer to determine if we need the VAT column
$hasVatCustomer = false;
$tempResult = $conn->query($sqlpaymentdetail);
while($tempRow = $tempResult->fetch_assoc()) {
    $invoiceID = $tempRow['tbl_invoice_idtbl_invoice'];
    $sqlCheckVat = "
    SELECT c.vat_num
    FROM tbl_invoice i
    LEFT JOIN tbl_customer c ON c.idtbl_customer = i.tbl_customer_idtbl_customer
    WHERE i.idtbl_invoice = '$invoiceID'
    ";
    $resultCheckVat = $conn->query($sqlCheckVat);
    $rowCheckVat = $resultCheckVat->fetch_assoc();
    $vat_num = isset($rowCheckVat['vat_num']) ? trim($rowCheckVat['vat_num']) : '';
    if(!empty($vat_num)){
        $hasVatCustomer = true;
        break;
    }
}

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
            <h5 style="margin-top: 10px;margin-bottom: 0px;">Everest Hardware (Pvt) Ltd Test</h5>
            Head Office : No.J174/20,Araliya Uyana,Kegalla.<br>
            Branch : No.107,Paragammana,Kegalla.<br>
            Tel: 0094-35-2232924 | Fax: 0094-77-9001546<br>support@everesthardware.com
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
                        <?php if($hasVatCustomer){ ?>
                        <th class="text-right">VAT</th>
                        <?php } ?>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i=1;
                    $total_payment = 0;
                    
                    while($rowpaymentdetails=$resultpaymentdetail->fetch_assoc()){ 
                        $invoiceID = $rowpaymentdetails['tbl_invoice_idtbl_invoice'];
                        
                        // Get invoice and customer order details
                        $sqlInvoiceInfo = "
                        SELECT 
                            i.total,
                            i.discount,
                            i.nettotal,
                            co.idtbl_customer_order,
                            co.vat,
                            co.podiscountpercentage,
                            c.vat_num
                        FROM tbl_invoice i
                        LEFT JOIN tbl_customer_order co ON co.idtbl_customer_order = i.tbl_customer_order_idtbl_customer_order
                        LEFT JOIN tbl_customer c ON c.idtbl_customer = i.tbl_customer_idtbl_customer
                        WHERE i.idtbl_invoice = '$invoiceID' AND i.status = 1
                        ";
                        $resultInvoiceInfo = $conn->query($sqlInvoiceInfo);
                        $rowinvoice = $resultInvoiceInfo->fetch_assoc();
                        
                        // Check if VAT customer
                        $vat_num = isset($rowinvoice['vat_num']) ? trim($rowinvoice['vat_num']) : '';
                        $isTaxCustomer = !empty($vat_num);
                        $actualvat = isset($rowinvoice['vat']) ? $rowinvoice['vat'] : $tax;
                        
                        $invoice_amount_without_vat = 0;
                        $vat_amount = 0;
                        $discount_amount = 0;
                        $payment_amount = 0;
                        
                        if($isTaxCustomer){
                            // Get invoice detail for VAT calculations
                            $recordID = $rowinvoice['idtbl_customer_order'];
                            $sqlinvoicedetail = "
                            SELECT 
                                `tbl_customer_order_detail`.`qty`,
                                `tbl_customer_order_detail`.`saleprice`,
                                `tbl_customer_order_detail`.`discount`
                            FROM `tbl_customer_order_detail`
                            WHERE `tbl_customer_order_detail`.`tbl_customer_order_idtbl_customer_order`='$recordID' 
                              AND `tbl_customer_order_detail`.`status`=1
                            ";
                            $resultinvoicedetail = $conn->query($sqlinvoicedetail);
                            
                            $fulltot = 0;
                            while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
                                $qtyValue = $rowinvoicedetail['qty'];
                                $base_price = $rowinvoicedetail['saleprice'] / (1 + ($actualvat / 100));
                                $base_discount = $rowinvoicedetail['discount'] / (1 + ($actualvat / 100));
                                $line_total_base = ($qtyValue * $base_price) - $base_discount;
                                $fulltot += $line_total_base;
                            }
                            
                            // Calculate amounts for VAT customer
                            $subtotal_before_discount = $fulltot;
                            $po_discount_amount = $subtotal_before_discount * ($rowinvoice["podiscountpercentage"] / 100);
                            $subtotal_after_po_discount = $subtotal_before_discount - $po_discount_amount;
                            $vat_amount = $subtotal_before_discount * ($actualvat / 100);
                            $grand_total = $subtotal_after_po_discount + $vat_amount;
                            
                            // For display
                            $invoice_amount_without_vat = $subtotal_before_discount; // Subtotal before VAT and discount
                            $discount_amount = $po_discount_amount;
                            $payment_amount = $grand_total;
                        } else {
                            // Non-VAT customer
                            $invoice_amount_without_vat = $rowinvoice['total'];
                            $vat_amount = 0;
                            $discount_amount = $rowpaymentdetails['discount'];
                            $payment_amount = $invoice_amount_without_vat - $discount_amount;
                        }
                        
                        $total_payment += $payment_amount;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo 'INV-'.$invoiceID; ?></td>
                        <td class="text-right"><?php echo number_format($invoice_amount_without_vat, 2); ?></td>
                        <?php if($hasVatCustomer){ ?>
                        <td class="text-right">
                            <?php 
                            if($isTaxCustomer){
                                echo number_format($vat_amount, 2) . ' (' . number_format($actualvat, 2) . '%)';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <?php } ?>
                        <td class="text-right"><?php echo number_format($discount_amount, 2); ?></td>
                        <td class="text-right"><?php echo number_format($payment_amount, 2); ?></td>
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