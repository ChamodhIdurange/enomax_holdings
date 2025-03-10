<?php 
require_once('../connection/db.php');

$validfrom = $_POST['validfrom'];
$validto = $_POST['validto'];
$customerID = $_POST['customer'];


$sql = "SELECT `c`.`returnamount`, `c`.`baltotalamount`, `c`.`payAmount`, `r`.`tbl_invoice_idtbl_invoice`, `c`.`settle` FROM `tbl_creditenote` AS `c` LEFT JOIN `tbl_creditenote_detail` AS `cd` ON (`c`.`idtbl_creditenote` = `cd`.`tbl_creditenote_idtbl_creditenote`) LEFT JOIN `tbl_return` AS `r` ON (`r`.`idtbl_return` = `cd`.`tbl_return_idtbl_return`) LEFT JOIN `tbl_invoice` AS `i` ON (`i`.`idtbl_invoice` = `r`.`tbl_invoice_idtbl_invoice`) LEFT JOIN `tbl_customer` AS `cu` ON (`cu`.`idtbl_customer` = `i`.`tbl_customer_idtbl_customer`)
WHERE `i`.`tbl_customer_idtbl_customer` = '$customerID'";

// if ($customerID > 0) {
//     $sql .= " AND ";
// }

// $sql .= " GROUP BY `u`.`idtbl_invoice`";
$result = $conn->query($sql);


if ($result->num_rows == 0) {
    echo "<div style=\"color: red; font-size:20px;\">No Records</div>";
    return;
}

$html = '<table class="table table-striped table-bordered table-sm small" id="reportTable">
    <thead>
        <tr>
            <th class="d-none">Invoice</th>
            <th class="text-right">Return Amount</th>
            <th class="text-right">Used Amount</th>
            <th class="text-right">Balance Amount</th>
            <th class="text-right">Status</th>
        </tr>
    </thead>
    <tbody>';

$totbalance = 0;

while($row = $result->fetch_assoc()) {
    $settled = 'Settled';
    $notsettled = 'Not Settled';
    $totbalance += $row['baltotalamount'];
    $html .= '<tr>
        <td class="d-none">INV-' . $row['tbl_invoice_idtbl_invoice'] . '</td>
        <td class="text-right">' . number_format($row['returnamount'], 2) . '</td>
        <td class="text-right">' . number_format($row['payAmount'], 2) . '</td>
        <td class="text-right">' . number_format($row['baltotalamount'], 2) . '</td>
        <td class="text-right">' . ($row['settle'] != 1 ? $notsettled : $settled) . '</td>
    </tr>';
}

$html .= '</tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-center"><strong>Total</strong></td>
            <td class="text-center"><strong>' . number_format($totbalance, 2) . '</strong></td>
        </tr>
    </tfoot>
</table>';

echo $html;
?>
