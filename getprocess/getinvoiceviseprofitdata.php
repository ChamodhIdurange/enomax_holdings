<?php
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];

$sql = "
SELECT  
    u.idtbl_invoice, 
    u.date, 
    u.invoiceno, 
    SUM(d.saleprice * d.qty) AS total_sale, 
    SUM(d.unitprice * d.qty) AS total_cost,
    SUM((d.saleprice * d.qty) - (d.unitprice * d.qty)) AS total_profit,
    IFNULL(SUM(d.discount), 0) AS total_discount,
    u.discount AS invoice_discount
FROM tbl_invoice AS u
LEFT JOIN tbl_invoice_detail AS d 
    ON d.tbl_invoice_idtbl_invoice = u.idtbl_invoice 
LEFT JOIN tbl_customer_order AS uf 
    ON u.tbl_customer_order_idtbl_customer_order = uf.idtbl_customer_order
WHERE u.status IN (1, 2) 
    AND d.status = '1'
    AND uf.delivered = '1'
    AND u.date BETWEEN '$fromdate' AND '$todate'
GROUP BY u.idtbl_invoice
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Po No</th>
                    <th class="text-center">Total Sale</th>
                    <th class="text-center">Total Cost</th>
                    <th class="text-center">Total Profit</th>
                    <th class="text-center">Profit With Discounts</th>
                </tr>
            </thead>
            <tbody>';

    $c = 0;
    $sum_sale = 0;
    $sum_cost = 0;
    $sum_profit = 0;
    $sum_discount = 0;
    $sum_invoice_discount = 0;
    $total_profit = 0;

    while ($row = $result->fetch_assoc()) {
        $c++;
        $sum_sale += $row['total_sale'];
        $sum_cost += $row['total_cost'];
        $sum_profit += $row['total_profit'];
        $sum_discount += $row['total_discount'];
        $sum_invoice_discount += $row['invoice_discount'];

        $total_profit+=($row['total_profit'] - $row['invoice_discount']-$row['total_discount']);

        echo '<tr>
                <td class="text-center">' . $c . '</td>
                <td class="text-center">' . $row['date'] . '</td>
                <td class="text-center">' . $row['invoiceno'] . '</td>
                <td class="text-right">' . number_format($row['total_sale'], 2, '.', ',') . '</td>
                <td class="text-right">' . number_format($row['total_cost'], 2, '.', ',') . '</td>
                <td class="text-right">' . number_format($row['total_profit'], 2, '.', ',') . '</td>
                <td class="text-right">' . number_format($row['total_profit'] - $row['invoice_discount']-$row['total_discount'], 2, '.', ',') . '</td>
            </tr>';
    }

    echo '</tbody>
          <tfoot>
              <tr>
                  <td colspan="3" class="text-center"><strong>Total</strong></td>
                  <td class="text-right"><strong>' . number_format($sum_sale, 2) . '</strong></td>
                  <td class="text-right"><strong>' . number_format($sum_cost, 2) . '</strong></td>
                  <td class="text-right"><strong>' . number_format($sum_profit, 2) . '</strong></td>
                  <td class="text-right"><strong>' . number_format($total_profit, 2) . '</strong></td>
              </tr>
          </tfoot>
      </table>';
} else {
    echo '<div class="alert alert-info" role="alert">No records found.</div>';
}
