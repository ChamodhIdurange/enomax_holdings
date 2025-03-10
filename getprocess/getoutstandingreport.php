<?php 
require_once('../connection/db.php');

$validfrom = $_POST['validfrom'];
$validto = $_POST['validto'];
$customerID = $_POST['customer'];
$repID = $_POST['rep'];
$searchType = $_POST['searchType'];
$aginvalue = $_POST['aginvalue'];

$customerarray = array();
$totalAmount = 0;
$totalPayAmount = 0;
$totalBalance = 0;

$sql = "SELECT `u`.`nettotal`, `u`.`idtbl_invoice`, `u`.`invoiceno`, `u`.`total`, `u`.`date`, `uc`.`name` AS `cusname`, `ue`.`name` AS `repname`, `uf`.`payamount`
        FROM `tbl_invoice` AS `u`
        LEFT JOIN `tbl_customer` AS `uc` ON `u`.`tbl_customer_idtbl_customer` = `uc`.`idtbl_customer`
        LEFT JOIN `tbl_customer_order` AS `ud` ON `u`.`tbl_customer_order_idtbl_customer_order` = `ud`.`idtbl_customer_order`
        LEFT JOIN `tbl_employee` AS `ue` ON `ud`.`tbl_employee_idtbl_employee` = `ue`.`idtbl_employee`
        LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `uf` ON `u`.`idtbl_invoice` = `uf`.`tbl_invoice_idtbl_invoice`
        WHERE `u`.`status`=1 
        AND `u`.`paymentcomplete`=0";

if (!empty($validfrom) && !empty($validto)) {
    $sql .= " AND `u`.`date` BETWEEN '$validfrom' AND '$validto'";
}
if ($searchType == '1') { 
    
} elseif ($searchType == '3') { 
    if ($customerID > 0) {
        $sql .= " AND `u`.`tbl_customer_idtbl_customer` = '$customerID'";
    }
} elseif ($searchType == '2') { 
    if ($repID > 0) {
        $sql .= " AND `ue`.`idtbl_employee` = '$repID'";
    }
} elseif ($searchType == '4') {
    
}
$sql .= " ORDER BY `uc`.`name` ASC";


$result = $conn->query($sql);

// if (!$result) {
//     die("Query failed: " . $conn->error);
// }

// echo $aginvalue;

if ($result->num_rows == 0) {
    echo "<div style=\"color: red; font-size:20px;\">No Records</div>";
    return;
}

while($row = $result->fetch_assoc()) {
    $date = new DateTime($row['date']);
    $today = new DateTime();  
    $interval = $today->diff($date);  
    $datecount = $interval->days;

    
    if ($aginvalue == 0 || 
        ($aginvalue == 1 && $datecount >= 0 && $datecount <= 15) ||
        ($aginvalue == 2 && $datecount > 15 && $datecount <= 30) ||
        ($aginvalue == 3 && $datecount > 30 && $datecount <= 45)) {
        
        array_push($customerarray, $row);
        $totalAmount += $row['nettotal'];
        $totalPayAmount += $row['payamount'];
        $balance = $row['nettotal'] - $row['payamount'];
        $totalBalance += $balance;
    }
}

$html = '<table class="table table-striped table-bordered table-sm small" id="outstandingReportTable">
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
    <tbody>';

foreach($customerarray as $rowcustomerarray) { 
    $balance = $rowcustomerarray['nettotal'] ?? 0 - $rowcustomerarray['payamount'] ?? 0;
    $html .= '<tr>
        <td>' . htmlspecialchars($rowcustomerarray['cusname']) . '</td>
        <td class="text-center">' . htmlspecialchars($rowcustomerarray['repname']) . '</td>
        <td class="text-center">' . htmlspecialchars($rowcustomerarray['date']) . '</td>
        <td class="text-center">' . $datecount . '</td>
        <td class="text-center">' . htmlspecialchars($rowcustomerarray['invoiceno']) . '</td>
        <td class="text-center">' . number_format(htmlspecialchars($rowcustomerarray['nettotal'] ?? 0)) . '</td>
        <td class="text-center">' . number_format(htmlspecialchars($rowcustomerarray['payamount'] ?? 0)) . '</td>
        <td class="text-center">' . number_format($balance) . '</td>
    </tr>';
}

$html .= '</tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-center"><strong>Total</strong></td>
            <td class="text-center"><strong>' . number_format($totalAmount) . '</strong></td>
            <td class="text-center"><strong>' . number_format($totalPayAmount) . '</strong></td>
            <td class="text-center"><strong>' . number_format($totalBalance) . '</strong></td>
        </tr>
    </tfoot>
</table>';

echo $html;
?>
