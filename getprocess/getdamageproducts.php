<?php
require_once('../connection/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fromdate = mysqli_real_escape_string($conn, $_POST['fromdate']);
    $todate = mysqli_real_escape_string($conn, $_POST['todate']);

    $query = "SELECT 
                r.idtbl_return,
                r.returndate,
                r.returntype,
                r.reason_type,
                r.damaged_reason,
                r.has_invoice,
                rd.idtbl_return_details,
                rd.qty,
                rd.unitprice,
                rd.discount,
                rd.total,
                p.idtbl_product,
                p.product_code,
                p.product_name,
                c.idtbl_customer,
                c.customer,
                c.idtbl_customer AS customer_code,
                i.invoiceno AS invoice_number
            FROM tbl_return r
            INNER JOIN tbl_return_details rd ON r.idtbl_return = rd.tbl_return_idtbl_return
            INNER JOIN tbl_product p ON rd.tbl_product_idtbl_product = p.idtbl_product
            INNER JOIN tbl_customer c ON r.tbl_customer_idtbl_customer = c.idtbl_customer
            LEFT JOIN tbl_invoice i ON r.tbl_invoice_idtbl_invoice = i.idtbl_invoice
            WHERE r.returndate BETWEEN '$fromdate' AND '$todate'
            AND r.status = 1
            AND r.acceptance_status = 1
            AND (
                r.returntype = 3 
                OR (r.returntype = 1 AND r.reason_type = 1)
            )
            ORDER BY r.returndate DESC, r.idtbl_return DESC";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo '<div class="alert alert-danger">Query Error: ' . mysqli_error($conn) . '</div>';
        exit;
    }

    $totalQty = 0;
    $totalAmount = 0;

    $html = '<table id="dataTable" class="display table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Return Date</th>
                        <th class="text-center">Invoice No</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Product Code</th>
                        <th class="text-left">Product Name</th>
                        <th class="text-center">Return Type</th>
                        <th class="text-left">Damage Reason</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total Amount</th>
                    </tr>
                </thead>
                <tbody>';

    $counter = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $returnTypeLabel = '';
        if ($row['returntype'] == 3) {
            $returnTypeLabel = '<span class="badge badge-danger p-2">Damaged Return</span>';
        } elseif ($row['returntype'] == 1 && $row['reason_type'] == 1) {
            $returnTypeLabel = '<span class="badge badge-warning p-2">Customer Return (Damaged)</span>';
        }

        // Get invoice number (only for customer returns with reason_type = 1)
        $invoiceNo = '-';
        if ($row['returntype'] == 1 && $row['reason_type'] == 1 && !empty($row['invoice_number'])) {
            $invoiceNo = '<strong>' . htmlspecialchars($row['invoice_number']) . '</strong>';
        }

        // Damage reason
        $damageReason = !empty($row['damaged_reason']) ? htmlspecialchars($row['damaged_reason']) : '-';

        // Customer info
        $customerInfo = htmlspecialchars($row['customer']);
        if (!empty($row['customer_code'])) {
            $customerInfo .= '<br><small class="text-muted">' . htmlspecialchars($row['customer_code']) . '</small>';
        }

        // Calculate totals
        $totalQty += $row['qty'];
        $totalAmount += $row['total'];

        $html .= '<tr>
                    <td class="text-center">' . $counter++ . '</td>
                    <td class="text-center">' . date('Y-m-d', strtotime($row['returndate'])) . '</td>
                    <td class="text-center">' . $invoiceNo . '</td>
                    <td class="text-left">' . $customerInfo . '</td>
                    <td class="text-center"><strong>' . htmlspecialchars($row['product_code']) . '</strong></td>
                    <td class="text-left">' . htmlspecialchars($row['product_name']) . '</td>
                    <td class="text-center">' . $returnTypeLabel . '</td>
                    <td class="text-left">' . $damageReason . '</td>
                    <td class="text-right">' . number_format($row['qty'], 2) . '</td>
                    <td class="text-right">' . number_format($row['unitprice'], 2) . '</td>
                    <td class="text-right"><strong>' . number_format($row['total'], 2) . '</strong></td>
                  </tr>';
    }

    if ($counter == 1) {
        $html .= '<tr><td colspan="11" class="text-center py-4">
                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                    <p class="text-muted">No damage products found for the selected date range</p>
                  </td></tr>';
    }

    $html .= '</tbody>
                <tfoot>
                    <tr class="bg-light">
                        <th colspan="8" class="text-right"><strong>Total:</strong></th>
                        <th class="text-right"><strong>' . number_format($totalQty, 2) . '</strong></th>
                        <th></th>
                        <th class="text-right"><strong>' . number_format($totalAmount, 2) . '</strong></th>
                    </tr>
                </tfoot>
            </table>';

    echo $html;

    mysqli_close($conn);
}
