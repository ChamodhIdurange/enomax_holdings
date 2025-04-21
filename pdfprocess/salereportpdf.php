<?php
session_start();
require_once('../connection/db.php');
require_once '../vendor/autoload.php';

ini_set('memory_limit', '999M');
ini_set('max_execution_time', '999');

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$validfrom = $_GET['validfrom'];
$validto = $_GET['validto'];
$productID = $_GET['product'];
$areaID = $_GET['area'];
$repID = $_GET['rep'];
$customerID = $_GET['customer'];
$searchType = $_GET['searchType'];

$sqlinfo = "SELECT `u`.`idtbl_invoice`,`u`.`invoiceno`, `u`.`total`, `ua`.`product_name`, `ub`.`area`, `uc`.`name` AS `cusname`, `ue`.`name` AS `repname`
            FROM `tbl_invoice` AS `u`
            LEFT JOIN `tbl_customer` AS `uc` ON `u`.`tbl_customer_idtbl_customer` = `uc`.`idtbl_customer`
            LEFT JOIN `tbl_customer_order` AS `uf` ON `u`.`tbl_customer_order_idtbl_customer_order` = `uf`.`idtbl_customer_order`
            LEFT JOIN `tbl_employee` AS `ue` ON `uf`.`tbl_employee_idtbl_employee` = `ue`.`idtbl_employee`
            LEFT JOIN `tbl_area` AS `ub` ON `u`.`tbl_area_idtbl_area` = `ub`.`idtbl_area`
            LEFT JOIN `tbl_invoice_detail` AS `ud` ON `u`.`idtbl_invoice` = `ud`.`tbl_invoice_idtbl_invoice`
            LEFT JOIN `tbl_product` AS `ua` ON `ud`.`tbl_product_idtbl_product` = `ua`.`idtbl_product`
            WHERE `u`.`date` BETWEEN '$validfrom' AND '$validto'";


if ($searchType == '2') { 
    if ($repID > 0) {
        $sqlinfo .= " AND `uf`.`tbl_employee_idtbl_employee` = '$repID'";
    }
} elseif ($searchType == '3') { 
    if ($productID > 0) {
        $sqlinfo .= " AND `ua`.`idtbl_product` = '$productID'";
    }
} elseif ($searchType == '4') { 
    if ($customerID > 0) {
        $sqlinfo .= " AND `u`.`tbl_customer_idtbl_customer` = '$customerID'";
    }
} elseif ($searchType == '5') { 
    if ($areaID > 0) {
        $sqlinfo .= " AND `u`.`tbl_area_idtbl_area` = '$areaID'";
    }
}

$sqlinfo .= " GROUP BY `u`.`idtbl_invoice`";

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
            <p>Outstanding Report';
    
    if ($searchType == 2) {
        $html .= ' - Rep Wise';
    } elseif ($searchType == 3) {
        $html .= ' - Product Wise';
    }  elseif ($searchType == 4) {
        $html .= ' - Customer Wise';
    }  elseif ($searchType == 5) {
        $html .= ' - Area Wise';
    } else {
        $html .= ' - All';
    }
    
    $html .= '</p>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="text-center">Sales Report Filtered from ' . $validfrom . ' to ' . $validto . '</h4>
            <hr class="border-dark">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <table class="tablec">
                <thead>
                    <tr>
                        <th class="thc">Invoice</th>
                        <th class="thc">Customer</th>
                        <th class="thc">Product</th>
                        <th class="thc">Rep</th>
                        <th class="thc">Area</th>
                        <th class="thc">Amount</th>
                    </tr>  
                </thead>
                <tbody>';

    $totalAmount = 0;
    while ($rowsqlinfo =     $resultsqlinfo->fetch_assoc()) {
        $html .= '
        <tr>
            <td class="tdc">' . $rowsqlinfo['invoiceno'] . '</td> 
            <td class="tdc">' . $rowsqlinfo['cusname'] . '</td> 
            <td class="tdc">' . $rowsqlinfo['product_name'] . '</td>
            <td class="tdc">' . $rowsqlinfo['repname'] . '</td>
            <td class="tdc">' . $rowsqlinfo['area'] . '</td>
            <td class="tdc">' . number_format($rowsqlinfo['total'], 2) . '</td>
        </tr>';
        $totalAmount += $rowsqlinfo['total'];
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

