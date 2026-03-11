<?php 
require_once('../connection/db.php');
$validfrom   = $_POST['validfrom'];
$validto     = $_POST['validto'];
$customerID  = $_POST['customer'];
$repID       = $_POST['rep'];
$searchType  = $_POST['searchType'];
$totalAmount = 0;

// Base query — includes date field for customer vise
$sql = "SELECT `u`.`idtbl_customer_order`, `u`.`cuspono`, `u`.`nettotal`, `u`.`date`,
               `ub`.`area`, `uf`.`customer` AS `cusname`, `ue`.`name` AS `repname`
        FROM `tbl_customer_order` AS `u`
        LEFT JOIN `tbl_customer` AS `uf` ON `u`.`tbl_customer_idtbl_customer` = `uf`.`idtbl_customer`
        LEFT JOIN `tbl_employee` AS `ue` ON `u`.`tbl_employee_idtbl_employee` = `ue`.`idtbl_employee`
        LEFT JOIN `tbl_area` AS `ub` ON `u`.`tbl_area_idtbl_area` = `ub`.`idtbl_area`
        WHERE `u`.`date` BETWEEN '$validfrom' AND '$validto'
        AND `u`.`status` = 1";

// Rep Vise filter — original logic, untouched
if ($searchType == 2 && $repID > 0) {
    $sql .= " AND `u`.`tbl_employee_idtbl_employee` = '$repID'";
}

// Customer Vise filter
if ($searchType == 4 && $customerID > 0) {
    $sql .= " AND `u`.`tbl_customer_idtbl_customer` = '$customerID'";
}

$sql .= " GROUP BY `u`.`idtbl_customer_order`";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<div style=\"color: red; font-size:20px;\">No Records</div>";
    return;
}

if ($searchType == 2) {

    $html = '<table class="table table-striped table-bordered table-sm small" id="reportTable">
        <thead>
            <tr>
                <th>Invoice</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Rep</th>
                <th class="text-center">Area</th>
                <th class="text-center">Amount</th>
            </tr>
        </thead>
        <tbody>';

    $totalAmount = 0;
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>' . $row['cuspono'] . '</td>
            <td class="text-center">' . $row['cusname'] . '</td>
            <td class="text-center">' . $row['repname'] . '</td>
            <td class="text-center">' . $row['area'] . '</td>
            <td class="text-right">' . number_format($row['nettotal'], 2) . '</td>
        </tr>';
        $totalAmount += $row['nettotal'];
    }

    $html .= '</tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center"><strong>Total</strong></td>
                <td class="text-right"><strong>' . number_format($totalAmount, 2) . '</strong></td>
            </tr>
        </tfoot>
    </table>';

} elseif ($searchType == 4) {

    $html = '<table class="table table-striped table-bordered table-sm small" id="reportTable">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Invoice / PO No</th>
                <th class="text-center">Invoice Date</th>
                <th class="text-center">Amount</th>
            </tr>
        </thead>
        <tbody>';

    $totalAmount = 0;
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        $invoiceDate = !empty($row['date']) ? date('d-m-Y', strtotime($row['date'])) : '-';
        $html .= '<tr>
            <td class="text-center">' . $counter++ . '</td>
            <td class="text-center">' . $row['cusname'] . '</td>
            <td class="text-center">' . $row['cuspono'] . '</td>
            <td class="text-center">' . $invoiceDate . '</td>
            <td class="text-right">' . number_format($row['nettotal'], 2) . '</td>
        </tr>';
        $totalAmount += $row['nettotal'];
    }

    $html .= '</tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center"><strong>Total</strong></td>
                <td class="text-right"><strong>' . number_format($totalAmount, 2) . '</strong></td>
            </tr>
        </tfoot>
    </table>';

}

echo $html;
?>