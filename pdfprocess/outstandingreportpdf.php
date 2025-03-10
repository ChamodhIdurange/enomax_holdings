<?php
session_start();
require_once('../connection/db.php');
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$validfrom = $_GET['validfrom'];
$validto =  $_GET['validto'];
$customerID = isset( $_GET['customer']) ?  $_GET['customer'] : 0;
$repID = isset( $_GET['rep']) ?  $_GET['rep'] : 0;
$searchType =  $_GET['searchType'];
$aginvalue = isset( $_GET['aginvalue']) ?  $_GET['aginvalue'] : 0;

$sql = "SELECT `u`.`idtbl_invoice`, `u`.`invoiceno`, `u`.`total`, `u`.`date`, `uc`.`name` AS `cusname`, 
        `ue`.`name` AS `repname`, `uf`.`payamount`
        FROM `tbl_invoice` AS `u`
        LEFT JOIN `tbl_customer` AS `uc` ON `u`.`tbl_customer_idtbl_customer` = `uc`.`idtbl_customer`
        LEFT JOIN `tbl_customer_order` AS `ud` ON `u`.`tbl_customer_order_idtbl_customer_order` = `ud`.`idtbl_customer_order`
        LEFT JOIN `tbl_employee` AS `ue` ON `ud`.`tbl_employee_idtbl_employee` = `ue`.`idtbl_employee`
        LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `uf` ON `u`.`idtbl_invoice` = `uf`.`tbl_invoice_idtbl_invoice`
        WHERE `u`.`status`=1 
        AND `u`.`paymentcomplete`=0 
        AND `u`.`date` BETWEEN '$validfrom' AND '$validto'";

if ($searchType == '3' && $customerID > 0) {
    $sql .= " AND `u`.`tbl_customer_idtbl_customer` = '$customerID'";
} elseif ($searchType == '2' && $repID > 0) {
    $sql .= " AND `ue`.`idtbl_employee` = '$repID'";
}

$sql .= " GROUP BY `u`.`idtbl_invoice`";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<div style=\"color: red; font-size:20px;\">No Records</div>";
    return;
}

$totalAmount = 0;
$totalPayAmount = 0;
$totalBalance = 0;

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outstanding Report</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .tablec {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .tablec th, .tablec td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        .tablec th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div>
        <h2 style="text-align: center;">ENOMAX Holdings (PVT) LTD</h2>
        <p style="text-align: center;">363/10/01, Malwatta, Kal-Eliya, Mirigama.<br>
        Tel: 033 4 950 951, Mobile: 0772710710, FAX: 0372221580<br>
        E-Mail: info@everesthardware.lk  Web: www.everesthardware.lk</p>
    </div>
    <hr>
    <div style="text-align: center;">
        <h3>Outstanding Report ';

if ($searchType == '3') {
    $html .= '- Customer Wise';
} elseif ($searchType == '2') {
    $html .= '- Rep Wise';
} else {
    $html .= '- All';
}

$html .= '</h3>
        <p>Filtered By: ' . htmlspecialchars($validfrom) . ' to ' . htmlspecialchars($validto) . '</p>
    </div>
    <table class="tablec">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Rep</th>
                <th>Date</th>
                <th>Days</th>
                <th>Invoice</th>
                <th>Invoice Total</th>
                <th>Pay Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    $date = new DateTime($row['date']);
    $today = new DateTime();  
    $interval = $today->diff($date);  
    $datecount = $interval->days;

    $balance = $row['total'] - $row['payamount'];

    // Apply aging value filters
    if ($aginvalue == 0 || 
        ($aginvalue == 1 && $datecount >= 0 && $datecount <= 15) ||
        ($aginvalue == 2 && $datecount > 15 && $datecount <= 30) ||
        ($aginvalue == 3 && $datecount > 30 && $datecount <= 45)) {

        $totalAmount += $row['total'];
        $totalPayAmount += $row['payamount'];
        $totalBalance += $balance;

        $html .= '
        <tr>
            <td>' . htmlspecialchars($row['cusname']) . '</td>
            <td>' . htmlspecialchars($row['repname']) . '</td>
            <td>' . htmlspecialchars($row['date']) . '</td>
            <td>DAYS ' . $datecount . '</td>
            <td>' . htmlspecialchars($row['invoiceno']) . '</td>
            <td>' . number_format($row['total'], 2) . '</td>
            <td>' . number_format($row['payamount'], 2) . '</td>
            <td>' . number_format($balance, 2) . '</td>
        </tr>';
    }
}

$html .= '
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Total</th>
                <th>' . number_format($totalAmount, 2) . '</th>
                <th>' . number_format($totalPayAmount, 2) . '</th>
                <th>' . number_format($totalBalance, 2) . '</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Outstanding_Report.pdf", ["Attachment" => 0]);

?>
