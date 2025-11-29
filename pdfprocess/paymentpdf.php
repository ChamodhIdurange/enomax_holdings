<?php
session_start();
require_once('../connection/db.php');
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$invoiceId=$_GET['invoiceId'];

// Get tax rate
$taxQuery = "SELECT `rate` FROM `tbl_tax` LIMIT 1";
$taxResult = $conn->query($taxQuery);
$tax = 0;
if ($taxResult && $taxResult->num_rows > 0) {
    $taxRow = $taxResult->fetch_assoc();
    $tax = $taxRow['rate'];
}

// Get invoice details with customer and order information
$sqlinvoice="SELECT `tbl_invoice`.*, `tbl_customer`.`vat_num`, `tbl_customer_order`.`vat`, `tbl_customer_order`.`podiscountpercentage`, `tbl_customer_order`.`idtbl_customer_order` 
FROM `tbl_invoice` 
LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
LEFT JOIN `tbl_customer_order` ON `tbl_customer_order`.`idtbl_customer_order` = `tbl_invoice`.`tbl_customer_order_idtbl_customer_order`
WHERE `tbl_invoice`.`idtbl_invoice`='$invoiceId'";
$resultinvoice=$conn->query($sqlinvoice);
$rowinvoice=$resultinvoice->fetch_assoc();

$nettotal = $rowinvoice['nettotal'];
$total = $rowinvoice['total'];
$discount = $rowinvoice['discount'];
$invoiceno = $rowinvoice['invoiceno'];

// Check if VAT customer
$vat_num = isset($rowinvoice['vat_num']) ? trim($rowinvoice['vat_num']) : '';
$isTaxCustomer = !empty($vat_num);
$actualvat = isset($rowinvoice['vat']) ? $rowinvoice['vat'] : $tax;

$final_total = 0;
$vat_amount = 0;
$subtotal_before_vat = 0;

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
    
    // Calculate VAT amount and final total
    $subtotal_before_discount = $fulltot;
    $po_discount_amount = $subtotal_before_discount * ($rowinvoice["podiscountpercentage"] / 100);
    $subtotal_after_po_discount = $subtotal_before_discount - $po_discount_amount;
    $vat_amount = $subtotal_before_discount * ($actualvat / 100);
    $final_total = $subtotal_after_po_discount + $vat_amount;
    $subtotal_before_vat = $subtotal_after_po_discount;
    $discount = $po_discount_amount;
} else {
    // Non-VAT customer
    $final_total = $nettotal;
    $vat_amount = 0;
    $subtotal_before_vat = $nettotal;
}

$sqlpayment="SELECT SUM(`ih`.`payamount`) AS 'paymentmade' FROM `tbl_invoice_payment` AS `ip` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `ih` ON (`ip`.`idtbl_invoice_payment` = `ih`.`tbl_invoice_payment_idtbl_invoice_payment`) WHERE `ih`.`tbl_invoice_idtbl_invoice`='$invoiceId' GROUP BY `ih`.`tbl_invoice_idtbl_invoice`";
$resultpayment=$conn->query($sqlpayment);
$rowpayment=$resultpayment->fetch_assoc();
$paymentmade = $rowpayment['paymentmade'];

$sqlpaymentbank="SELECT `id`.`method`, `id`.`amount`, `id`.`receiptno`, `id`.`chequeno` FROM `tbl_invoice_payment_detail` AS `id` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `ih` ON (`id`.`tbl_invoice_payment_idtbl_invoice_payment` = `ih`.`tbl_invoice_payment_idtbl_invoice_payment`) WHERE `tbl_invoice_idtbl_invoice`='$invoiceId'";
$resultpaymentbank=$conn->query($sqlpaymentbank);

$html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ENOMAX Holdings (PVT) LTD</title>
        <style>
            body {
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans",
                    sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                line-height: 1.5;
            }
            .tg  {border-collapse:collapse;border-spacing:0;}
            .tg td{font-family:Arial, sans-serif;font-size:14px;padding:5px 10px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
            .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:5px 10px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
            .tg .tg-btmp{font-weight:bold;color:#000;text-align:left;vertical-align:top}
            .tg .tg-0lax{text-align:left;vertical-align:top}

            .receipt-header {
                font-family: Arial, sans-serif;
                margin-bottom: 20px;
                text-align: right;
                position: relative;
            }

            .head-label {
                background-color: #000;
                color: #FFF;
                padding: 5px 15px;
                border-radius: 5px;
                width: 160px;
                text-align: center;
                position: absolute;
                top: -30px;
                right: 0;
            }

            h4 {
                margin: 30px 0 0;
                font-weight: bold;
            }

            p {
                margin: 0;
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
        <table border="0" width="100%">
            <tr>
                <td style="vertical-align: bottom;padding-bottom: 17px;">Invoice No: '.$invoiceno.'</td>
                <td style="text-align: right;">
                    <div class="receipt-header">
                        <div class="header-content">
                            <div class="head-label">PAYMENT RECEIPT</div>
                        </div>
                        <h4>ENOMAX Holdings (PVT) LTD</h4>
                        <p>
                            
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border-color: #000;margin-top:5px; margin-bottom:5px;">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="tg" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice No</th>
                                <th style="text-align: right">Subtotal</th>';
                                if($isTaxCustomer){
                                    $html.='<th style="text-align: right">VAT ('.$actualvat.'%)</th>';
                                }
                                $html.='<th style="text-align: right">Discount</th>
                                <th style="text-align: right">Invoice Amount</th>
                                <th style="text-align: right">Payment</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $i=1;
                            $html.='<tr>
                                <td>'.$i.'</td>
                                <td>'.$invoiceno.'</td>
                                <td style="text-align: right">'.number_format($subtotal_before_vat, 2).'</td>';
                                
                                if($isTaxCustomer){
                                    $html.='<td style="text-align: right">'.number_format($vat_amount, 2).'</td>';
                                }
                                
                                $html.='<td style="text-align: right">'.number_format($discount, 2).'</td>
                                <td style="text-align: right">'.number_format($final_total, 2).'</td>
                                <td style="text-align: right">'.number_format($paymentmade, 2).'</td>
                            </tr>';
                        $html.='</tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="50%" style="vertical-align: top;">
                    <div style="margin-bottom: 15px; margin-top: 15px;">Payment Methods</div>
                    <table class="tg" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Receipt</th>
                                <th>Cheque No</th>
                                <th style="text-align: right">Payment</th>
                            </tr>
                        </thead>
                        <tbody>'; 
                        while($rowpaymentbank=$resultpaymentbank->fetch_assoc()){
                            $html.='<tr>
                                <td>';
                                if($rowpaymentbank['method']==1){
                                    $html.='Cash';
                                }else if($rowpaymentbank['method']==2){
                                    $html.='Cheque';
                                }else if($rowpaymentbank['method']==3){
                                    $html.='Credit Note';
                                }
                            $html.='</td>
                                <td>'.$rowpaymentbank['receiptno'].'</td>
                                <td>'.$rowpaymentbank['chequeno'].'</td>
                                <td style="text-align: right">'.number_format($rowpaymentbank['amount'], 2).'</td>
                            </tr>';
                        }
                        $html.='<tbody>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table width="100%">';
                        if($isTaxCustomer){
                            $html.='<tr>
                                <td width="65%" style="text-align: right">Subtotal (Before VAT)</td>
                                <td style="text-align: right">Rs. '.number_format($subtotal_before_vat, 2).'</td>
                            </tr>
                            <tr>
                                <td width="65%" style="text-align: right">VAT ('.$actualvat.'%)</td>
                                <td style="text-align: right">Rs. '.number_format($vat_amount, 2).'</td>
                            </tr>
                            <tr>
                                <td width="65%" style="text-align: right; font-weight: bold;">Invoice Total</td>
                                <td style="text-align: right; font-weight: bold;">Rs. '.number_format($final_total, 2).'</td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr style="border-color: #ccc;"></td>
                            </tr>';
                        }
                        $html.='<tr>
                            <td width="65%" style="text-align: right">Payment Made</td>
                            <td style="text-align: right">Rs. '.number_format($paymentmade, 2).'</td>
                        </tr>
                        <tr>
                            <td width="65%" style="text-align: right; font-weight: bold;">Balance</td>
                            <td style="text-align: right; font-weight: bold;">Rs. '.number_format($final_total - $paymentmade, 2).'</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';
// echo $html;
$dompdf->loadHtml($html);
// $dompdf->setPaper('21.5cm', '27.5cm', 'portrait');
$dompdf->render();
$dompdf->stream("Payment_Receipt_".$invoiceno.".pdf", ["Attachment" => 0]);