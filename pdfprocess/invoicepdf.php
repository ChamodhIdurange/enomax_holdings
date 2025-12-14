<?php
//invoicepdf.php
session_start();
require_once('../connection/db.php');
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$today = date('Y-m-d');
$recordID = $_GET['id'];

$fulltot = 0;
$discount = 0;
$totalpayment = 0;

$sqlinvoiceinfo = "SELECT `tbl_invoice`.`discount`, `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`invoiceno`, 
`tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`paymentcomplete`, 
`tbl_locations`.`locationname`, `tbl_customer`.`customer`, `tbl_customer`.`address`, 
`tbl_customer`.`phone`, `tbl_customer`.`vat_num`, 
`tbl_employee`.`name` AS `saleref`, `tbl_employee`.`phone` AS 'salesrepphone', 
`tbl_area`.`area`, `tbl_user`.`name` as `username`, 
`tbl_invoice`.`tbl_customer_idtbl_customer`, `tbl_customer_order`.`cuspono`,`tbl_customer_order`.`vat`,`tbl_customer_order`.`podiscountpercentage`
FROM `tbl_invoice` 
LEFT JOIN `tbl_locations` ON `tbl_locations`.`idtbl_locations`=`tbl_invoice`.`tbl_locations_idtbl_locations`
LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer`
LEFT JOIN `tbl_customer_order` ON `tbl_customer_order`.`idtbl_customer_order`=`tbl_invoice`.`tbl_customer_order_idtbl_customer_order`
LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_customer_order`.`tbl_employee_idtbl_employee`
LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area`
LEFT JOIN `tbl_user` ON `tbl_user`.`idtbl_user`=`tbl_invoice`.`tbl_user_idtbl_user`
WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`='$recordID'";

$resultinvoiceinfo = $conn->query($sqlinvoiceinfo);
if (!$resultinvoiceinfo) {
    die("Database Error: " . $conn->error);
}
if ($resultinvoiceinfo->num_rows == 0) {
    die("No invoice found with ID: " . $recordID);
}
$rowinvoiceinfo = $resultinvoiceinfo->fetch_assoc();

$customerID = $rowinvoiceinfo['tbl_customer_idtbl_customer'];
$customerPhone = $rowinvoiceinfo['phone'];
$customername = $rowinvoiceinfo['customer'];
$location = $rowinvoiceinfo['locationname'];
$customeraddress = $rowinvoiceinfo['address'];
$paymentcomplete = $rowinvoiceinfo['paymentcomplete'];
$invoiceno = $rowinvoiceinfo['invoiceno'];
$pono = $rowinvoiceinfo['cuspono'];
$invoiceDate = $rowinvoiceinfo['date'];
$empname = $rowinvoiceinfo['saleref'];
$empphone = $rowinvoiceinfo['salesrepphone'];
$vat_num = isset($rowinvoiceinfo['vat_num']) ? trim($rowinvoiceinfo['vat_num']) : '';
$isTaxCustomer = !empty($vat_num);
$actualvat = $rowinvoiceinfo['vat'];

$sqlinvoicedetail = "SELECT `tbl_product`.`product_name`, `tbl_product`.`product_code`, 
`tbl_invoice_detail`.`qty`,`tbl_invoice_detail`.`freeqty`, `tbl_invoice_detail`.`saleprice`, `tbl_invoice_detail`.`discount` 
FROM `tbl_invoice_detail` 
LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product`
WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID' AND `tbl_invoice_detail`.`status`=1";
$resultinvoicedetail = $conn->query($sqlinvoicedetail);

$sqlpayments = "SELECT SUM(amount) as total_paid FROM tbl_payment WHERE tbl_invoice_idtbl_invoice='$recordID' AND status=1";
$resultpayments = $conn->query($sqlpayments);
if ($resultpayments) {
    $rowpayments = $resultpayments->fetch_assoc();
    $totalpayment = $rowpayments['total_paid'] ?? 0;
}

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ENOMAX Holdings (PVT) LTD - Invoice</title>
<style>
@page {
    margin: 1cm 0.8cm 1cm 0.8cm;
}

body {
    font-family: Arial, sans-serif;
    font-size: 9px;
    margin-top: 2.5cm;
}

.header-box {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #005EB8;
    color: #FFFFFF;
    padding: 0.3cm;
    border-bottom: 2px solid #000;
    text-align: center;
    height: 1.3cm;
}
.company-name {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 0.1cm;
}
.company-info {
    font-size: 8px;
}

.watermark {
    position: fixed;
    top: 45%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    font-size: 72px;
    color: rgba(0, 166, 81, 0.08);
    font-weight: bold;
    z-index: -1;
    text-align: center;
    width: 100%;
}

.customer-section {
    border: 1px solid #000;
    padding: 0.3cm;
    min-height: 3cm;
    float: left;
    width: 55%;
    margin-bottom: 0.2cm;
    line-height: 1.6;
}
.invoice-details {
    float: right;
    width: 40%;
    margin-bottom: 0.2cm;
}
.tax-invoice-label {
    background-color: #005EB8;
    padding: 0.2cm;
    text-align: center;
    font-weight: bold;
    font-size: 11px;
    margin-bottom: 0.1cm;
    color: #FFFFFF;
}
table.items {
    width: 100%;
    border-collapse: collapse;
    clear: both;
    margin-top: 0.2cm;
    table-layout: fixed;
}
table.items th {
    background-color: #005EB8;
    color: #FFFFFF;
    font-weight: bold;
    padding: 0.15cm;
    border: 1px solid #000;
    font-size: 9px;
}
table.items td {
    border: 1px solid #000;
    padding: 0.1cm;
    font-size: 8.5px;
    word-wrap: break-word;
}
.totals-section {
    float: right;
    width: 40%;
    margin-top: 0.2cm;
}
.totals-row {
    border: 1px solid #000;
    padding: 0.1cm 0.2cm;
    margin-bottom: 1px;
    text-align: right;
}
.footer-notes {
    clear: both;
    margin-top: 0.3cm;
    font-size: 7px;
    line-height: 1.3;
}
.signature-section {
    margin-top: 0.5cm;
    border-top: 2px solid #005EB8;
    padding-top: 0.3cm;
    clear: both;
}
.signature-box {
    width: 31%;
    float: left;
    text-align: center;
    padding: 0.2cm;
    margin: 0 1%;
}
.signature-line {
    border-bottom: 1px solid #333;
    height: 1.2cm;
    margin-bottom: 0.2cm;
}
.signature-label {
    font-weight: bold;
    font-size: 9px;
    color: #005EB8;
}
.signature-sublabel {
    font-size: 7px;
    color: #666;
    margin-top: 0.05cm;
}
</style>
</head>
<body>

<div class="header-box">
    <div class="company-name">ENOMAX HOLDINGS (PVT) LTD</div>
    <div class="company-info">
        No.46, Garden City, Minuwangoda Road, Ja-ela.<br>
        Tel: 011 3468568
    </div>
</div>

<div class="watermark">ENOMAX</div>

<div class="customer-section">
    <strong>Customer ID:</strong> ' . htmlspecialchars($customerID) . '<br>
    <strong style="font-size: 11px;">' . htmlspecialchars($customername) . '</strong><br>
    ' . htmlspecialchars($customeraddress) . '<br>
    <strong>Tel:</strong> ' . htmlspecialchars($customerPhone);

if ($isTaxCustomer) {
    $html .= '<br><strong>VAT No:</strong> ' . htmlspecialchars($vat_num);
}

$html .= '
</div>

<div class="invoice-details">';

if ($isTaxCustomer) {
    $html .= '<div class="tax-invoice-label">TAX INVOICE</div>';
} else {
    $html .= '<div class="tax-invoice-label">INVOICE</div>';
}

$html .= '
  <table style="width:100%; border-collapse:collapse; margin-top:0.2cm;">
    <tr><td style="font-weight:bold;">Date :</td><td>' . htmlspecialchars($invoiceDate) . '</td></tr>
    <tr><td style="font-weight:bold;">Invoice No :</td><td>' . htmlspecialchars($invoiceno) . '</td></tr>
    <tr><td style="font-weight:bold;">Purchase Order No :</td><td>' . htmlspecialchars($pono) . '</td></tr>
    <tr><td style="font-weight:bold;">Store Location :</td><td>' . htmlspecialchars($location) . '</td></tr>
    <tr><td style="font-weight:bold;">Sales Executive :</td><td>' . htmlspecialchars($empname) . '</td></tr>
    <tr><td style="font-weight:bold;">Contact :</td><td>' . htmlspecialchars($empphone) . '</td></tr>';

if ($isTaxCustomer) {
    $html .= '<tr><td style="font-weight:bold;">VAT No :</td><td>109575771</td></tr>';
}

$html .= '
</table>
</div>

<table class="items">
<thead>
<tr>
    <th style="width:17%;">CODE</th>
    <th style="width:35%;">DESCRIPTION</th>
    <th style="width:8%;">QTY</th>
    <th style="width:8%;">FREE QTY</th>
    <th style="width:13%;">UNIT PRICE</th>
    <th style="width:10%;">DIS.</th>
    <th style="width:17%;">AMOUNT</th>
</tr>
</thead>
<tbody>';

$count = 0;
while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
    $count++;
    $qty = (float)$rowinvoicedetail['qty'];
    $freeqty = (float)$rowinvoicedetail['freeqty'];
    $saleprice = (float)$rowinvoicedetail['saleprice'];
    $linediscount = (float)$rowinvoicedetail['discount'];
    
    // CORRECT VAT LOGIC: Extract base price from VAT-inclusive price
    if ($isTaxCustomer) {
        $base_price = $saleprice / (1 + ($actualvat / 100));
        $base_discount = $linediscount / (1 + ($actualvat / 100));
    } else {
        // For non-VAT customers, use price as-is
        $base_price = $saleprice;
        $base_discount = $linediscount;
    }
    
    // Calculate line total (base price before VAT)
    $line_total_base = ($qty * $base_price) - $base_discount;
    $fulltot += $line_total_base;

    $html .= '
    <tr>
        <td>' . $count . ' ' . htmlspecialchars($rowinvoicedetail['product_code']) . '</td>
        <td>' . htmlspecialchars($rowinvoicedetail['product_name']) . '</td>
        <td align="center">' . $qty . '</td>
        <td align="center">' . $freeqty . '</td>
        <td align="right">' . number_format($base_price, 2) . '</td>
        <td align="right">' . number_format($base_discount, 2) . '</td>
        <td align="right">' . number_format($line_total_base, 2) . '</td>
    </tr>';
}

$html .= '
</tbody>
</table>';

// Get invoice-level discount
$invoice_discount = (float)$rowinvoiceinfo["podiscountpercentage"];

// Calculate totals based on customer type
$html .= '<div class="totals-section">';

if ($isTaxCustomer) {
    // VAT CUSTOMER CALCULATION (same logic as porderpdf.php)
    // 1. Subtotal (before any discounts) - already calculated as $fulltot
    $subtotal_before_discount = $fulltot;
    
    // 2. Extract base discount from VAT-inclusive discount
    $base_invoice_discount = $subtotal_before_discount * ($invoice_discount / 100);
    
    // 3. Net amount before VAT
    $net_before_vat = $subtotal_before_discount - $base_invoice_discount;
    
    // 4. Calculate VAT on subtotal before discount
    $vat_amount = $subtotal_before_discount * ($actualvat / 100);
    
    // 5. Grand total
    $grand_total = $net_before_vat + $vat_amount;
    
    $html .= '
    <div class="totals-row">Subtotal (Before VAT): <strong>' . number_format($subtotal_before_discount, 2) . '</strong></div>
    <div class="totals-row">Discount: <strong>' . number_format($base_invoice_discount, 2) . '</strong></div>
    <div class="totals-row">Net Before VAT: <strong>' . number_format($net_before_vat, 2) . '</strong></div>
    <div class="totals-row">VAT (' . number_format($actualvat, 2) . '%): <strong>' . number_format($vat_amount, 2) . '</strong></div>
    <div class="totals-row" style="background-color:#E6F4EA;">Grand Total: <strong>' . number_format($grand_total, 2) . '</strong></div>';
    
} else {
    // NON-VAT CUSTOMER CALCULATION (simpler)
    $net_total_before_discount = $fulltot;
    $net_total = $net_total_before_discount - $invoice_discount;
    
    $html .= '
    <div class="totals-row">Sub Total: <strong>' . number_format($net_total_before_discount, 2) . '</strong></div>
    <div class="totals-row">Discount: <strong>' . number_format($invoice_discount, 2) . '</strong></div>
    <div class="totals-row" style="background-color:#E6F4EA;">Net Total: <strong>' . number_format($net_total, 2) . '</strong></div>';
}

$html .= '
</div>

<div class="footer-notes">
    <div>* Payment must be completed within 45 days</div>
    <div>* Received the above goods in good condition</div>
</div>

<div class="signature-section">
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">Prepared By</div>
        <div class="signature-sublabel">ENOMAX HOLDINGS (PVT) LTD</div>
    </div>
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">Authorized By</div>
    </div>
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">Customer Signature</div>
        <div class="signature-sublabel">Received By</div>
    </div>
    <div style="clear:both;"></div>
</div>

</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Invoice_" . $invoiceno . ".pdf", ["Attachment" => 0]);
exit;