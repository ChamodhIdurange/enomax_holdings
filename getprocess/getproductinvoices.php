<?php 
require_once('../connection/db.php');

$productId = $_POST['productId'];

$totalAmount = 0;
$totalPayAmount = 0;
$totalBalance = 0;
$customerarray = array();


$sql = "SELECT `u`.`nettotal`, `u`.`idtbl_invoice`, `u`.`invoiceno`, `u`.`total`, `u`.`date`, `uc`.`name` AS `cusname`, `ua`.`qty`, `ua`.`saleprice`
        FROM `tbl_invoice` AS `u`
        LEFT JOIN `tbl_invoice_detail` AS `ua` ON `u`.`idtbl_invoice` = `ua`.`tbl_invoice_idtbl_invoice`
        LEFT JOIN `tbl_customer` AS `uc` ON `u`.`tbl_customer_idtbl_customer` = `uc`.`idtbl_customer`
        WHERE `u`.`status`=1 
        AND `ua`.`tbl_product_idtbl_product`= '$productId'";

if (isset($_POST['customerId']) && !empty($_POST['customerId'])) {
    $customerId = $_POST['customerId'];

    $sql .= " AND `u`.`tbl_customer_idtbl_customer`= '$customerId'";
}

$sql .= " GROUP BY `u`.`idtbl_invoice`";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<div style=\"color: red; font-size:20px;\">No Records</div>";
    return;
}

while($row = $result->fetch_assoc()) {
    $date = new DateTime($row['date']);
    $today = new DateTime();  
    $interval = $today->diff($date);  
    $datecount = $interval->days;

    
    array_push($customerarray, $row);
    $totalAmount += $row['nettotal'];
}

$html = '<table class="table table-striped table-bordered table-sm small" id="outstandingReportTable">
    <thead>
        <tr>
            <th>Customer</th>
            <th class="text-center">Date</th>
            <th class="text-center">Invoice</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Invoice Total</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>';

foreach($customerarray as $rowcustomerarray) { 
    $balance = $rowcustomerarray['nettotal'] ?? 0 - $rowcustomerarray['payamount'] ?? 0;
    $html .= '<tr>
        <td>' . htmlspecialchars($rowcustomerarray['cusname']) . '</td>
        <td class="text-center">' . htmlspecialchars($rowcustomerarray['date']) . '</td>
        <td class="text-center">' . htmlspecialchars($rowcustomerarray['invoiceno']) . '</td>
        <td class="text-center">' . htmlspecialchars($rowcustomerarray['qty']) . '</td>
        <td class="text-center">' . number_format(htmlspecialchars($rowcustomerarray['nettotal'] ?? 0)) . '</td>
        <td class="text-center"> <button class="btn btn-outline-dark btn-sm btnView mr-1" id="' . $rowcustomerarray['idtbl_invoice'] .'"><i class="fas fa-eye"></i></button> </td>
    </tr>';
}

$html .= '</tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-center"><strong>Total</strong></td>
            <td class="text-center"><strong>' . number_format($totalAmount) . '</strong></td>
        </tr>
    </tfoot>
</table>';

echo $html;
?>
