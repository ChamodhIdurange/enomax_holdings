<?php 
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];
$today = date("Y-m-d");

// AND `u`.`date` BETWEEN '$fromdate' AND '$todate'

$sql =    "SELECT  
                `d`.`tbl_product_idtbl_product`, 
                `u`.`cuspono`, 
                SUM((`d`.`saleprice` - `d`.`unitprice`) * `d`.`dispatchqty`) AS `total_profit`,
                SUM(`d`.`dispatchqty`) AS `total_qty`,
                `p`.`product_name`
            FROM `tbl_customer_order` AS `u`  
            LEFT JOIN `tbl_customer_order_detail` AS `d` 
                ON `d`.`tbl_customer_order_idtbl_customer_order` = `u`.`idtbl_customer_order` 
            LEFT JOIN `tbl_product` AS `p` 
                ON `p`.`idtbl_product` = `d`.`tbl_product_idtbl_product` 
            WHERE `u`.`status` IN (1, 2) 
                AND `u`.`delivered` = '1'
                AND `d`.`status` = '1'
                AND `u`.`date` BETWEEN '$fromdate' AND '$todate'
            GROUP BY `d`.`tbl_product_idtbl_product`";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
            <thead>
                 <tr>
                    <th>#</th>
                    <th class="text-center">Po No</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Profit</th>
                </tr>
            </thead>
            <tbody>';
    $c=0;
    $full_profit=0;
    
    while ($rowstock = $result->fetch_assoc()) {
        $full_profit += $rowstock['total_profit'];
        $c++;
        echo '<tr>
                <td class="text-center">' . $c . '</td>
                <td class="text-center">' . $rowstock['cuspono'] . '</td>
                <td class="text-center">' . $rowstock['product_name'] . '</td>
                <td class="text-center">' . $rowstock['total_qty'] . '</td>
                <td class="text-right">' . number_format($rowstock['total_profit'], 2, '.', ',')  . '</td>
            </tr>';
    }
    echo '</tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center"><strong>Total</strong></td>
                        <td class="text-right"><strong>' . number_format($full_profit, 2) . '</strong></td>
                    </tr>
                </tfoot>
            </table>';
} else {
    echo '<div class="alert alert-info" role="alert">No records found.</div>';
}
?>
