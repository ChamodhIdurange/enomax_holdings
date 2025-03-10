<?php 
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];
$today = date("Y-m-d");
$replist = $_POST['replist'];
$replist = implode(", ", $replist);

$sqlstock =    "SELECT 
                    e.name,
                    
                    COALESCE(SUM(co.nettotal ), 0) AS pendingTotal,
                    COALESCE(SUM(CASE WHEN co.delivered = '1' THEN co.nettotal ELSE 0 END), 0) AS deliveredTotal,

                    COALESCE((SELECT SUM(r.total) 
                            FROM tbl_return r 
                            WHERE r.status = '1' 
                                AND r.acceptance_status = '0' 
                                AND r.returndate BETWEEN '$fromdate' AND '$todate' 
                                AND r.tbl_employee_idtbl_employee = e.idtbl_employee), 0) AS pendingReturns,

                    COALESCE((SELECT SUM(r.total) 
                            FROM tbl_return r 
                            WHERE r.status = '1' 
                                AND r.acceptance_status = '1' 
                                AND r.returndate BETWEEN '$fromdate' AND '$todate' 
                                AND r.tbl_employee_idtbl_employee = e.idtbl_employee), 0) AS acceptedReturns

                    -- (COALESCE(SUM(CASE WHEN co.delivered = '1' THEN co.nettotal ELSE 0 END), 0) + 
                    -- COALESCE((SELECT SUM(r.total) 
                    --         FROM tbl_return r 
                    --         WHERE r.status = '1' 
                    --             AND r.acceptance_status = '1' 
                    --             AND r.returndate BETWEEN '$fromdate' AND '$todate' 
                    --             AND r.tbl_employee_idtbl_employee = e.idtbl_employee), 0)) AS fullnettotal

                FROM tbl_customer_order AS co
                LEFT JOIN tbl_employee AS e ON e.idtbl_employee = co.tbl_employee_idtbl_employee
                WHERE co.status = '1'  
                AND co.date BETWEEN '$fromdate' AND '$todate'
                AND co.tbl_employee_idtbl_employee IN ($replist)
                GROUP BY co.tbl_employee_idtbl_employee";
$resultstock = $conn->query($sqlstock);

if ($resultstock->num_rows > 0) {
    echo '<table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Rep Name</th>
                    <th class="text-center">Sales</th>
                    <th class="text-center">Approved Sales</th>
                    <th class="text-center">Return</th>
                    <th class="text-center">Approved Return</th>
                    <th class="text-center">Net Sale</th>
                </tr>
            </thead>
            <tbody>';
    $c=0;
    $total=0;
    $salesTotal=0;
    $approvedSalesTotal=0;
    $returnTotal=0;
    $approvedReturnTotal=0;
    while ($rowstock = $resultstock->fetch_assoc()) {
        $total += $rowstock['deliveredTotal'] - $rowstock['acceptedReturns'];
        $salesTotal += $rowstock['pendingTotal'];
        $approvedSalesTotal += $rowstock['deliveredTotal'];
        $returnTotal += $rowstock['pendingReturns'];
        $approvedReturnTotal += $rowstock['acceptedReturns'];
        $c++;
        echo '<tr>
                <td class="text-center">' . $c . '</td>
                <td class="text-center">' . $rowstock['name'] . '</td>
                <td class="text-right">' . number_format($rowstock['pendingTotal'], 2, '.', ',')  . '</td>
                <td class="text-right">' . number_format($rowstock['deliveredTotal'], 2, '.', ',')  . '</td>
                <td class="text-right">' . number_format($rowstock['pendingReturns'], 2, '.', ',') . '</td>
                <td class="text-right">' . number_format($rowstock['acceptedReturns'], 2, '.', ',')  . '</td>
                <td class="text-right">' . number_format($rowstock['deliveredTotal'] - $rowstock['acceptedReturns'], 2, '.', ',') . '</td>
            </tr>';
    }
    echo '</tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center"><strong>Total</strong></td>
                        <td class="text-right"><strong>' . number_format($salesTotal, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($approvedSalesTotal, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($returnTotal, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($approvedReturnTotal, 2) . '</strong></td>
                        <td class="text-right"><strong>' . number_format($total, 2) . '</strong></td>
                    </tr>
                </tfoot>
            </table>';
} else {
    echo '<div class="alert alert-info" role="alert">No records found.</div>';
}
?>
