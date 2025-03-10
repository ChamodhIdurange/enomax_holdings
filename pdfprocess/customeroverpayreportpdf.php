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
$validto = $_GET['validto'];
$customerID = $_GET['customer'];

$sqlinfo = "SELECT `c`.`returnamount`, `c`.`baltotalamount`, `c`.`payAmount`, `r`.`tbl_invoice_idtbl_invoice`, `c`.`settle` FROM `tbl_creditenote` AS `c` LEFT JOIN `tbl_creditenote_detail` AS `cd` ON (`c`.`idtbl_creditenote` = `cd`.`tbl_creditenote_idtbl_creditenote`) LEFT JOIN `tbl_return` AS `r` ON (`r`.`idtbl_return` = `cd`.`tbl_return_idtbl_return`) LEFT JOIN `tbl_invoice` AS `i` ON (`i`.`idtbl_invoice` = `r`.`tbl_invoice_idtbl_invoice`) LEFT JOIN `tbl_customer` AS `cu` ON (`cu`.`idtbl_customer` = `i`.`tbl_customer_idtbl_customer`)
WHERE `i`.`tbl_customer_idtbl_customer` = '$customerID'";

$resultsqlinfo = $conn->query($sqlinfo);

if ($resultsqlinfo->num_rows > 0) {

    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sales Report</title>
        <style>
            @page {
                margin-top: 5px;
            }
            body {
                margin: 0px;
                padding: 0px;
                font-family: Arial, sans-serif;
                width: 100%;
                font-size: small;
            }
            .tablec {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                font-size: 10px;
                border: 1px solid #ddd;
            }
            .thc, .tdc {
                padding: 5px;
                text-align: center;
            }
            .thc {
                background-color: #f2f2f2;
            }
            .tdc {
                border: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
    <div class="row">
        <div class="col-12">
            <table class="w-100 tableprint">
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td><strong>ENOMAX Holdings (PVT) LTD.</strong><br>
                            363/10/01, Malwatta, Kal-Eliya, Mirigama.<br>
                            Tel: 033 4 950 951, Mobile: 0772710710, FAX: 0372221580<br>
                            <strong>E-Mail: info@everesthardware.lk  Web: www.everesthardware.lk</strong></td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>            
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <p>Outstanding Report - Customer Wise';
    

    
    $html .= '</p>
            <br>
        </div>
    </div>
    <div class="row">
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <table class="tablec">
                <thead>
                    <tr>
                        <th class="thc">Invoice</th>
                        <th class="thc">Return Amount</th>
                        <th class="thc">Used Amount</th>
                        <th class="thc">Balance Amount</th>
                        <th class="thc">Status</th>
                    </tr>  
                </thead>
                <tbody>';
                $totalAmount = 0;
                while ($rowsqlinfo = $resultsqlinfo->fetch_assoc()) {
                    $settled = 'Settled';
                    $notsettled = 'Not Settled';
                    $html .= '
                    <tr>
                    <td class="tdc">INV-' . $rowsqlinfo['tbl_invoice_idtbl_invoice'] . '</td>
                    <td class="tdc">' . number_format($rowsqlinfo['returnamount'], 2) . '</td>
                    <td class="tdc">' . number_format($rowsqlinfo['payAmount'], 2) . '</td>
                    <td class="tdc">' . number_format($rowsqlinfo['baltotalamount'], 2) . '</td>
                    <td class="tdc">' . ($rowsqlinfo['settle'] != 1 ? $notsettled : $settled) . '</td>
                    </tr>';
                    $totalAmount += $rowsqlinfo['baltotalamount'];
                }

                $html .= '
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="thc"><strong>Total</strong></td>
                        <td class="thc"><strong>' . number_format($totalAmount, 2) . '</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </body>
    </html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Sales_Report.pdf", ["Attachment" => 0]);

} else {
    echo "No records found for the selected date range.";
}
?>

