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
        co.podiscountpercentage AS invoice_discount_percentage
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
            <th class="text-center">Grand Total</th>
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
    $invoiceDiscountPercentage = floatval($row['invoice_discount_percentage']);
    $vatRateFromOrder = isset($row['vat_rate']) ? floatval($row['vat_rate']) : 0.0;
    $actualvat = ($vatRateFromOrder > 0) ? $vatRateFromOrder : $taxRate;

    $isTaxCustomer = ($vatNum !== '');

    $detailStmt->bind_param('i', $invoiceID);
    $detailStmt->execute();
    $detailRes = $detailStmt->get_result();

    $fulltot = 0.0; // Subtotal before discount (base price before VAT)

    while ($item = $detailRes->fetch_assoc()) {
        $qty = floatval($item['qty']);
        $saleprice = floatval($item['saleprice']);
        $linediscount = floatval($item['discount']);

        // SAME LOGIC AS INVOICE PDF: Extract base price from VAT-inclusive price
        if ($isTaxCustomer && $actualvat > 0) {
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
    }

    // Calculate totals - SAME LOGIC AS INVOICE PDF
    if ($isTaxCustomer && $actualvat > 0) {
        // VAT CUSTOMER CALCULATION
        // 1. Subtotal (before any discounts)
        $subtotal_before_discount = $fulltot;

        // 2. Calculate base invoice discount
        $base_invoice_discount = $subtotal_before_discount * ($invoiceDiscountPercentage / 100);

        // 3. Net amount before VAT
        $net_before_vat = $subtotal_before_discount - $base_invoice_discount;

        // 4. Calculate VAT on subtotal before discount
        $vat_amount = $subtotal_before_discount * ($actualvat / 100);

        // 5. Grand total
        $grand_total = $net_before_vat + $vat_amount;

        // For display purposes
        $invoiceValue = $net_before_vat;
        $vatAmount = $vat_amount;
    } else {
        // NON-VAT CUSTOMER - shouldn't appear in this report but included for completeness
        $invoiceValue = $fulltot - ($fulltot * ($invoiceDiscountPercentage / 100));
        $vatAmount = 0.0;
    }

    $totalInvoice += $invoiceValue;
    $totalVAT += $vatAmount;

    $totalGrand = $totalInvoice + $totalVAT;

    $output .= '
    <tr>
        <td>' . $count++ . '</td>
        <td>' . htmlspecialchars($invoiceDate) . '</td>
        <td>' . htmlspecialchars($invoiceno) . '</td>
        <td>' . htmlspecialchars($customerName) . '</td>
        <td>' . htmlspecialchars($vatNum) . '</td>
        <td class="text-center">' . number_format($actualvat, 2) . '</td>
        <td class="text-right">' . number_format($invoiceValue, 2) . '</td>
        <td class="text-right">' . number_format($vatAmount, 2) . '</td>
        <td class="text-right">' . number_format($invoiceValue + $vatAmount, 2) . '</td>
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
            <td class="text-right">' . number_format($totalInvoice + $totalVAT, 2) . '</td>
        </tr>
    </tfoot>
</table>
';

echo $output;
exit;
