<?php 
require_once('../connection/db.php');

$validfrom = $_POST['validfrom'];
$validto = $_POST['validto'];
$paymentMethod = $_POST['paymentMethod'];
$totalAmount = 0;

$methodFilter = "";
if ($paymentMethod != 0) {
    $methodFilter = "AND ipd.method = $paymentMethod";
}

$sql =    "SELECT tb1.idtbl_invoice_payment, tb1.date, tb1.receiptno, tb1.payamount, tb2.invoiceno, tb2.cusname, tb2.empname, 'INVOICE' AS type, tb1.method
            FROM (
                SELECT ip.idtbl_invoice_payment, ip.date, ipd.receiptno, ipd.amount AS payamount, ipd.method
                FROM tbl_invoice_payment_detail AS ipd
                LEFT JOIN  tbl_invoice_payment AS ip
                ON ip.idtbl_invoice_payment = ipd.tbl_invoice_payment_idtbl_invoice_payment
                WHERE ipd.status = 1
                    AND ip.date  BETWEEN '$validfrom' AND '$validto'
                    $methodFilter
            ) AS tb1
            LEFT JOIN (
                SELECT ip.idtbl_invoice_payment, i.invoiceno, c.customer AS cusname, e.name AS empname
                FROM tbl_invoice_payment AS ip
                LEFT JOIN tbl_invoice_payment_has_tbl_invoice AS iphi
                ON iphi.tbl_invoice_payment_idtbl_invoice_payment = ip.idtbl_invoice_payment
                LEFT JOIN tbl_invoice AS i
                ON i.idtbl_invoice = iphi.tbl_invoice_idtbl_invoice
                LEFT JOIN tbl_customer_order AS co
                ON i.tbl_customer_order_idtbl_customer_order = co.idtbl_customer_order
                LEFT JOIN tbl_customer AS c
                ON i.tbl_customer_idtbl_customer = c.idtbl_customer
                LEFT JOIN  tbl_employee AS e
                ON co.tbl_employee_idtbl_employee = e.idtbl_employee
                GROUP BY ip.idtbl_invoice_payment
            ) AS tb2
            ON tb1.idtbl_invoice_payment = tb2.idtbl_invoice_payment
            WHERE 
            tb2.idtbl_invoice_payment IS NOT NULL";


// $sql .= " GROUP BY `u`.`idtbl_invoice`";

// echo $sql;
$result = $conn->query($sql);


if ($result->num_rows == 0) {
    echo "<div style=\"color: red; font-size:20px;\">No Records</div>";
    return;
}

$html = '<table class="table table-striped table-bordered table-sm small" id="reportTable">
    <thead>
        <tr>
            <th>Invoice</th>
            <th class="text-center">Customer</th>
            <th class="text-center">Receipt</th>
            <th class="text-center">Method</th>
            <th class="text-center">Amount</th>
        </tr>
    </thead>
    <tbody>';

$totalAmount = 0;

while($row = $result->fetch_assoc()) {
    $methodDisplay = '';
    switch ($row['method']) {
        case 1:
            $methodDisplay = 'Cash';
            break;
        case 2:
            $methodDisplay = 'Bank/Cheque';
            break;
        case 3:
            $methodDisplay = 'Credit Note';
            break;
        default:
            $methodDisplay = 'Unknown';
    }

    $html .= '<tr>
        <td>' . $row['invoiceno'] . '</td>
        <td class="text-center">' . $row['cusname'] . '</td>
        <td class="text-center">' . $row['receiptno'] . '</td>
        <td class="text-center">' . $methodDisplay. '</td>
        <td class="text-center">' . number_format($row['payamount'], 2) . '</td>
    </tr>';
    $totalAmount += $row['payamount'];
}

$html .= '</tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-center"><strong>Total</strong></td>
            <td class="text-center"><strong>' . number_format($totalAmount, 2) . '</strong></td>
        </tr>
    </tfoot>
</table>';

echo $html;
?>

