<?php
require_once('../connection/db.php');

$validfrom    = $_POST['validfrom']    ?? '';
$validto      = $_POST['validto']      ?? '';
$deliveryfrom = $_POST['deliveryfrom'] ?? '';
$deliveryto   = $_POST['deliveryto']   ?? '';
$customerID   = $_POST['customer']     ?? 0;
$repID        = $_POST['rep']          ?? 0;
$searchType   = $_POST['searchType']   ?? 0;
$aginvalue    = $_POST['aginvalue']    ?? 0;

$customerarray   = [];
$totalAmount     = 0;
$totalPayAmount  = 0;
$totalBalance    = 0;

$sql = "
SELECT 
    u.nettotal,
    u.idtbl_invoice,
    u.invoiceno,
    u.total,
    u.date,
    uc.customer AS cusname,
    ue.name AS repname,
    COALESCE(uf.payamount, 0) AS payamount
FROM tbl_invoice u
LEFT JOIN tbl_customer uc 
    ON u.tbl_customer_idtbl_customer = uc.idtbl_customer
LEFT JOIN tbl_customer_order ud 
    ON u.tbl_customer_order_idtbl_customer_order = ud.idtbl_customer_order
LEFT JOIN tbl_employee ue 
    ON ud.tbl_employee_idtbl_employee = ue.idtbl_employee
LEFT JOIN tbl_invoice_payment_has_tbl_invoice uf 
    ON u.idtbl_invoice = uf.tbl_invoice_idtbl_invoice
WHERE u.status = 1
AND u.paymentcomplete = 0
";

if (!empty($validfrom) && !empty($validto)) {
    $sql .= " AND u.date BETWEEN '$validfrom' AND '$validto'";
}

if ($searchType == '3' && $customerID > 0) {
    $sql .= " AND u.tbl_customer_idtbl_customer = '$customerID'";
}

if ($searchType == '2' && $repID > 0) {
    $sql .= " AND ue.idtbl_employee = '$repID'";
}

if (!empty($deliveryfrom) && !empty($deliveryto)) {
    $sql .= " 
        AND ud.delivereddatetime IS NOT NULL
        AND ud.delivereddatetime BETWEEN 
            '$deliveryfrom 00:00:00' 
            AND '$deliveryto 23:59:59'
    ";
}

$sql .= " ORDER BY uc.customer ASC";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<div style='color:red;font-size:20px;'>No Records</div>";
    return;
}

while ($row = $result->fetch_assoc()) {

    $invoiceDate = new DateTime($row['date']);
    $today       = new DateTime();
    $datecount   = $today->diff($invoiceDate)->days;

    if (
        $aginvalue == 0 ||
        ($aginvalue == 1 && $datecount <= 15) ||
        ($aginvalue == 2 && $datecount > 15 && $datecount <= 30) ||
        ($aginvalue == 3 && $datecount > 30 && $datecount <= 45)
    ) {
        $customerarray[] = $row;

        $totalAmount    += $row['nettotal'];
        $totalPayAmount += $row['payamount'];
        $totalBalance   += ($row['nettotal'] - $row['payamount']);
    }
}

$html = '
<table class="table table-striped table-bordered table-sm small" id="outstandingReportTable">
<thead>
<tr>
    <th>Customer</th>
    <th class="text-center">Rep</th>
    <th class="text-center">Date</th>
    <th class="text-center">Days</th>
    <th class="text-center">Invoice</th>
    <th class="text-center">Invoice Total</th>
    <th class="text-center">Pay Amount</th>
    <th class="text-center">Balance</th>
</tr>
</thead>
<tbody>
';

foreach ($customerarray as $row) {

    $invoiceDate = new DateTime($row['date']);
    $today       = new DateTime();
    $datecount   = $today->diff($invoiceDate)->days;

    $netTotal  = $row['nettotal'] ?? 0;
    $payAmount = $row['payamount'] ?? 0;
    $balance   = $netTotal - $payAmount;

    $html .= '
    <tr>
        <td>' . htmlspecialchars($row['cusname']) . '</td>
        <td class="text-center">' . htmlspecialchars($row['repname']) . '</td>
        <td class="text-center">' . htmlspecialchars($row['date']) . '</td>
        <td class="text-center">' . $datecount . '</td>
        <td class="text-center">' . htmlspecialchars($row['invoiceno']) . '</td>
        <td class="text-center">' . number_format($netTotal, 2) . '</td>
        <td class="text-center">' . number_format($payAmount, 2) . '</td>
        <td class="text-center">' . number_format($balance, 2) . '</td>
    </tr>';
}

$html .= '
</tbody>
<tfoot>
<tr>
    <td colspan="5" class="text-center"><strong>Total</strong></td>
    <td class="text-center"><strong>' . number_format($totalAmount, 2) . '</strong></td>
    <td class="text-center"><strong>' . number_format($totalPayAmount, 2) . '</strong></td>
    <td class="text-center"><strong>' . number_format($totalBalance, 2) . '</strong></td>
</tr>
</tfoot>
</table>';

echo $html;
