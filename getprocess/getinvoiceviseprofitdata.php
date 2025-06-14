<?php 
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];
$today = date("Y-m-d");

// AND `u`.`date` BETWEEN '$fromdate' AND '$todate'

// $sql =    "SELECT  
//                 `u`.`idtbl_customer_order`, 
//                 `u`.`date`, 
//                 `u`.`cuspono`, 
//                 SUM((`d`.`saleprice` - `d`.`unitprice`) * `d`.`dispatchqty`) AS `total_profit`,
//                 `u`.`nettotal`
//             FROM `tbl_customer_order` AS `u`  
//             LEFT JOIN `tbl_customer_order_detail` AS `d` 
//                 ON `d`.`tbl_customer_order_idtbl_customer_order` = `u`.`idtbl_customer_order` 
//             LEFT JOIN `tbl_product` AS `p` 
//                 ON `p`.`idtbl_product` = `d`.`tbl_product_idtbl_product` 
//             WHERE `u`.`status` IN (1, 2) 
//                 AND `u`.`delivered` = '1'
//                 AND `d`.`status` = '1'
//                 AND `u`.`date` BETWEEN '$fromdate' AND '$todate'
//             GROUP BY `u`.`idtbl_customer_order`";

$sql =    "SELECT  
                `u`.`idtbl_invoice`, 
                `u`.`date`, 
                `u`.`invoiceno`, 
                SUM((`d`.`saleprice` - `d`.`unitprice`) * `d`.`qty`) AS `total_profit`,
                SUM((`d`.`unitprice`) * `d`.`qty`) AS `total_cost`,
                `u`.`nettotal`,
                `d`.`discount`,
                `u`.`total`
            FROM `tbl_invoice` AS `u`  
            LEFT JOIN `tbl_invoice_detail` AS `d` 
                ON `d`.`tbl_invoice_idtbl_invoice` = `u`.`idtbl_invoice` 
            LEFT JOIN `tbl_product` AS `p` 
                ON `p`.`idtbl_product` = `d`.`tbl_product_idtbl_product` 
            WHERE `u`.`status` IN (1, 2) 
                AND `d`.`status` = '1'
                AND `u`.`status` = '1'
                AND `u`.`date` BETWEEN '$fromdate' AND '$todate'
            GROUP BY `u`.`idtbl_invoice`";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
            <thead>
                 <tr>
                    <th>#</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Po No</th>
                    <th class="text-center">Total Sale</th>
                    <th class="text-center">Total Sale (Net)</th>
                    <th class="text-center">Total Cost</th>
                    <th class="text-center">Total Profit</th>
                    <th class="text-center">Profit With Discounts</th>
                </tr>
            </thead>
            <tbody>';
    $c=0;
    $full_profit=0;
    $full_pnettotal=0;
    $full_ptotal=0;
    $full_pdiscount=0;
    $full_pcost=0;
    
    while ($rowstock = $result->fetch_assoc()) {
        $full_profit += $rowstock['total_profit'];
        $full_pnettotal += $rowstock['nettotal'];
        $full_ptotal += $rowstock['total'];
        $full_pdiscount += $rowstock['discount'];
        $full_pcost += $rowstock['total_cost'];
        $c++;
        echo '<tr>
                <td class="text-center">' . $c . '</td>
                <td class="text-center">' . $rowstock['date'] . '</td>
                <td class="text-center">' . $rowstock['invoiceno'] . '</td>
                <td class="text-right">' . number_format($rowstock['total'], 2, '.', ',')  . '</td>
                <td class="text-right">' . number_format($rowstock['nettotal'], 2, '.', ',')  . '</td>
                <td class="text-right">' . number_format($rowstock['total_cost'], 2, '.', ',')  . '</td>
                <td class="text-right">' . number_format($rowstock['total_profit'], 2, '.', ',')  . '</td>
                <td class="text-right">' . number_format($rowstock['total_profit'] - $rowstock['discount'], 2, '.', ',')  . '</td>
            </tr>';
    }
    echo '</tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-center"><strong>Total</strong></td>
                        <td class="text-right"><strong>' . number_format($full_ptotal, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($full_pnettotal, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($full_pcost, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($full_profit, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($full_profit - $full_pdiscount, 2) . '</strong></td>
                    </tr>
                </tfoot>
            </table>';
} else {
    echo '<div class="alert alert-info" role="alert">No records found.</div>';
}
?>
