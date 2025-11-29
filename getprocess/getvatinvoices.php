<?php

include "../connection/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<div class="alert alert-danger text-center">Invalid request method.</div>';
    exit;
}

$fromdate = isset($_POST['fromdate']) ? trim($_POST['fromdate']) : '';
$todate   = isset($_POST['todate']) ? trim($_POST['todate']) : '';
$customerlist = isset($_POST['customerlist']) ? $_POST['customerlist'] : [];

if (!is_array($customerlist)) {
    $customerlist = array_filter(array_map('trim', explode(',', $customerlist)));
}

$taxRate = 0.0;
$taxQuery = "SELECT `vat_rate` FROM `tbl_vat` LIMIT 1";
$taxResult = $conn->query($taxQuery);
if ($taxResult && $taxResult->num_rows > 0) {
    $r = $taxResult->fetch_assoc();
    $taxRate = floatval($r['vat_rate']);
}

if ($fromdate === '') {
    $fromdate = '1900-01-01';
}
if ($todate === '') {
    $todate = '9999-12-31';
}

$fromdate_esc = $conn->real_escape_string($fromdate);
$todate_esc   = $conn->real_escape_string($todate);

if (!empty($customerlist) && !in_array('all', $customerlist)) {
    $escapedIds = array_map(function ($id) use ($conn) {
        return "'" . $conn->real_escape_string($id) . "'";
    }, $customerlist);

    $whereCustomers = "AND i.tbl_customer_idtbl_customer IN (" . implode(',', $escapedIds) . ") 
                       AND c.vat_num <> '' 
                       AND c.vat_num IS NOT NULL";
} else {
    $whereCustomers = "AND c.vat_num <> '' 
                       AND c.vat_num IS NOT NULL";
}

$sql = "
    SELECT 
        i.idtbl_invoice, 
        i.date,
        i.invoiceno, 
        i.vatamount,
        COALESCE(c.customer, c.customer) AS customer, 
        c.vat_num, 
        i.nettotal AS invoice_value,
        co.vat AS vat_rate,
        COALESCE(i.discount, 0) AS invoice_discount
    FROM tbl_invoice i
    INNER JOIN tbl_customer c 
        ON i.tbl_customer_idtbl_customer = c.idtbl_customer
    LEFT JOIN tbl_customer_order co 
        ON i.tbl_customer_order_idtbl_customer_order = co.idtbl_customer_order
    WHERE i.date BETWEEN '$fromdate_esc' AND '$todate_esc'
    $whereCustomers
    ORDER BY i.invoiceno ASC
";

$result = $conn->query($sql);
if (!$result) {
    die('<div class="alert alert-danger text-center">SQL Error (invoices): ' . htmlspecialchars($conn->error) . '</div>');
}

if ($result->num_rows == 0) {
    echo '<div class="alert alert-warning text-center">No invoices found for the selected period.</div>';
    exit;
}

$output = '
<table id="dataTable" class="display table table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th class="text-center">Invoice No</th>
            <th class="text-center">Customer</th>
            <th class="text-center">VAT Number</th>
            <th class="text-center">VAT Rate(%)</th>
            <th class="text-center">Invoice Value</th>
            <th class="text-center">VAT Amount</th>
        </tr>
    </thead>
    <tbody>
';

$count = 1;
$totalInvoice = 0.0; 
$totalVAT = 0.0;     

$detailStmt = $conn->prepare("
    SELECT qty, saleprice, discount 
    FROM tbl_invoice_detail 
    WHERE tbl_invoice_idtbl_invoice = ? AND status = 1
");

if (!$detailStmt) {
    die('<div class="alert alert-danger text-center">Prepare Error (details): ' . htmlspecialchars($conn->error) . '</div>');
}

while ($row = $result->fetch_assoc()) {
    $invoiceID = $row['idtbl_invoice'];
    $invoiceDate = $row['date'];
    $invoiceno = $row['invoiceno'];
    $customerName = $row['customer'];
    $vatNum = trim($row['vat_num']);
    $invoiceDiscount = floatval($row['invoice_discount']);
    $vatRateFromOrder = isset($row['vat_rate']) ? floatval($row['vat_rate']) : 0.0;
    $vatRate = ($vatRateFromOrder > 0) ? $vatRateFromOrder : $taxRate;

    $isTaxCustomer = ($vatNum !== '');

    $detailStmt->bind_param('i', $invoiceID);
    $detailStmt->execute();
    $detailRes = $detailStmt->get_result();

    $subTotalExVat = 0.0; 

    while ($item = $detailRes->fetch_assoc()) {
        $qty = floatval($item['qty']);
        $saleprice = floatval($item['saleprice']);
        $lineDiscount = floatval($item['discount']);

        if ($isTaxCustomer && $vatRate > 0) {
            $unitExVat = $saleprice / (1 + ($vatRate / 100));
        } else {
            $unitExVat = $saleprice;
        }

        $lineTotalExVat = ($unitExVat * $qty) - $lineDiscount;
        $subTotalExVat += $lineTotalExVat;
    }

    $netBeforeVat = $subTotalExVat - $invoiceDiscount; 
    if ($netBeforeVat < 0) {
        $netBeforeVat = 0; 
    }

    $vatAmount = 0.0;
    $grandTotal = $netBeforeVat; 

    if ($isTaxCustomer && $vatRate > 0) {
        $vatAmount = $netBeforeVat * ($vatRate / 100);
        $grandTotal = $netBeforeVat + $vatAmount;
    }

    $totalInvoice += $netBeforeVat; 
    $totalVAT += $vatAmount;

    $output .= '
    <tr>
        <td>' . $count++ . '</td>
        <td>' . htmlspecialchars($invoiceDate) . '</td>
        <td>' . htmlspecialchars($invoiceno) . '</td>
        <td>' . htmlspecialchars($customerName) . '</td>
        <td>' . htmlspecialchars($vatNum) . '</td>
        <td class="text-center">' . number_format($vatRate, 2) . '</td>
        <td class="text-right">' . number_format($netBeforeVat, 2) . '</td>
        <td class="text-right">' . number_format($vatAmount, 2) . '</td>
    </tr>';
}

$detailStmt->close();

$output .= '
    </tbody>
    <tfoot>
        <tr style="font-weight:bold;">
            <td colspan="6" class="text-right">Total</td>
            <td class="text-right">' . number_format($totalInvoice, 2) . '</td>
            <td class="text-right">' . number_format($totalVAT, 2) . '</td>
        </tr>
    </tfoot>
</table>
';

echo $output;
exit;
