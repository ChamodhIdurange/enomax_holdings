<?php 
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];
$agingval = $_POST['agingval'];
$replist = $_POST['replist'];
$replist = implode(", ", $replist);
$fulltotal = 0;
$today = date("Y-m-d");

$sqloutstanding =    "SELECT 
                    `e`.`name` AS 'empname',
                    `c`.`name` AS 'customername',
                    `c`.`address`,
                    `c`.`idtbl_customer`,
                    `co`.`date`,
                    `co`.`remark`,
                    DATEDIFF(CURDATE(), `co`.`date`) AS `date_difference`,
                    `i`.`invoiceno`,
                    `i`.`nettotal`,
                    `d`.`deliverDate`,
                    COALESCE((SELECT SUM(`r`.`payamount`) 
                            FROM tbl_invoice_payment_has_tbl_invoice r 
                            WHERE r.tbl_invoice_idtbl_invoice = i.idtbl_invoice), 0) AS payedamount


                FROM tbl_customer_order AS co
                LEFT JOIN tbl_employee AS e ON e.idtbl_employee = co.tbl_employee_idtbl_employee
                LEFT JOIN tbl_customer AS c ON c.idtbl_customer = co.tbl_customer_idtbl_customer
                LEFT JOIN tbl_invoice AS i ON i.tbl_customer_order_idtbl_customer_order = co.idtbl_customer_order
                LEFT JOIN tbl_customer_order_delivery_data AS d ON d.tbl_customer_order_idtbl_customer_order = co.idtbl_customer_order
                WHERE co.status = '1'  
                AND i.status = '1'
                AND co.date BETWEEN '$fromdate' AND '$todate'
                AND co.delivered = '1'
                AND co.tbl_employee_idtbl_employee IN ($replist)
                AND i.paymentcomplete = '0'
                AND DATEDIFF(CURDATE(), `co`.`date`) >= $agingval
                ORDER BY `c`.`name` ASC";
$resultstock = $conn->query($sqloutstanding);

if ($resultstock->num_rows > 0) {
    $c = 1;
    $customerId = -99;
    $oldCustomerId = -99;
    $netInvoiceAmount = 0;
    $netDeductions = 0;
    $netBalance = 0;
    $fulltotal = 0;

    echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
            <div style="text-align: left;">
                <h4 style="margin: 0; font-size: 14px;">EVEREST HARDWARE CO. (PVT) LTD</h4>
                <p style="margin: 2px 0; font-size: 10px;">
                    #363/10/01, Malwatte, Kal-Eliya (Mirigama) <br>
                    033 4 950 951 | <a href="mailto:info@everesthardware.lk" style="font-size: 10px;">info@everesthardware.lk</a>
                </p>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 16px;">Customer Outstanding Report</h2>
            </div>
        </div>';

    while ($row = $resultstock->fetch_assoc()) {
        $customerId = $row['idtbl_customer'];

        if ($c != 1 && $oldCustomerId != $customerId) {
            echo '
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f1f1f1; font-weight: bold; font-size: 10px;">
                            <td colspan="7" class="text-center">Total</td>
                            <td class="text-right">' . number_format($netInvoiceAmount, 2) . '</td>
                            <td class="text-right">' . number_format($netDeductions, 2) . '</td>
                            <td class="text-right">' . number_format($netBalance, 2) . '</td>
                        </tr>
                    </tfoot>
                </table>
            </div>';

            $fulltotal += $netBalance;
            $netInvoiceAmount = 0;
            $netDeductions = 0;
            $netBalance = 0;
            $c = 1;
        }

        $netInvoiceAmount += $row['nettotal'];
        $netDeductions += $row['payedamount'];
        $netBalance += $row['nettotal'] - $row['payedamount'];

        if ($oldCustomerId != $customerId) {
            $oldCustomerId = $customerId;

            echo '
               <div style="background: #fff; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0px; margin-bottom: 3px; border-left: 3px solid #004085;">
                <h3 style="margin: 0 0 4px; font-size: 13px; font-weight: bold; color: #004085;">' . $row['customername'] . '</h3>
                <p style="margin: 0 0 6px; font-size: 10px; color: #555;">' . $row['address'] . '</p>

                <table class="table table-bordered table-sm nowrap" style="background: #fff; font-size: 10px; width: 100%;">
                    <thead style="background: #004085; color: #fff;">
                        <tr>
                            <th class="text-center" style="padding: 3px;">No</th>
                            <th class="text-center" style="padding: 3px;">Invoice</th>
                            <th class="text-center" style="padding: 3px;">Delivery</th>
                            <th class="text-center" style="padding: 3px;">Date</th>
                            <th class="text-center" style="padding: 3px;">Aging</th>
                            <th class="text-center" style="padding: 3px;">Remark</th>
                            <th class="text-center" style="padding: 3px;">Rep</th>
                            <th class="text-right" style="padding: 3px;">Amount</th>
                            <th class="text-right" style="padding: 3px;">Deductions</th>
                            <th class="text-right" style="padding: 3px;">Balance</th>
                        </tr>
                    </thead>
                <tbody>';
        }

        echo '
            <tr>
                <td class="text-center" style="padding: 3px;">' . $c . '</td>
                <td class="text-center" style="padding: 3px;">' . $row['invoiceno'] . '</td>
                <td class="text-center" style="padding: 3px;">' . $row['deliverDate'] . '</td>
                <td class="text-center" style="padding: 3px;">' . $row['date'] . '</td>
                <td class="text-center" style="padding: 3px;">' . $row['date_difference'] . '</td>
                <td class="text-center" style="padding: 3px;">' . $row['remark'] . '</td>
                <td class="text-center" style="padding: 3px;">' . $row['empname'] . '</td>
                <td class="text-right" style="padding: 3px;">' . number_format($row['nettotal'], 2, '.', ',') . '</td>
                <td class="text-right" style="padding: 3px;">' . number_format($row['payedamount'], 2, '.', ',') . '</td>
                <td class="text-right" style="padding: 3px;">' . number_format($row['nettotal'] - $row['payedamount'], 2, '.', ',') . '</td>
            </tr>';
        $c++;
    }
    $fulltotal += $netBalance;
    echo '
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f1f1; font-weight: bold; font-size: 10px;">
                <td colspan="7" class="text-center">Total</td>
                <td class="text-right">' . number_format($netInvoiceAmount, 2) . '</td>
                <td class="text-right">' . number_format($netDeductions, 2) . '</td>
                <td class="text-right">' . number_format($netBalance, 2) . '</td>
            </tr>
        </tfoot>
    </table>
    <h4 style="float: right; margin-top: 15px; font-size: 12px;">Gross Total: Rs.' . number_format($fulltotal, 2) . '</h4>
</div>';
} else {
    echo '<div class="alert alert-info" role="alert" style="font-size: 12px;">No records found.</div>';
}

?>

