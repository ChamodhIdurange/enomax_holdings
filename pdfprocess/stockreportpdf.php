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

$fromdate = $_GET['fromdate'];

$sqlstock = "SELECT `p`.`retail`, `sp`.`category` as `subcat`, `gp`.`category` as `groupcat`, `pc`.`category` as `maincat`, `p`.`product_name`, SUM(`s`.`qty`) AS `qty`, `m`.`name` 
             FROM `tbl_stock` as `s` 
             LEFT JOIN `tbl_product` as `p` ON (`p`.`idtbl_product`=`s`.`tbl_product_idtbl_product`) 
             LEFT JOIN `tbl_sizes` AS `m` ON (`m`.`idtbl_sizes` = `p`.`tbl_sizes_idtbl_sizes`) 
             LEFT JOIN `tbl_product_category` AS `pc` ON (`p`.`tbl_product_category_idtbl_product_category` = `pc`.`idtbl_product_category`) 
             LEFT JOIN `tbl_sub_product_category` AS `sp` ON (`p`.`tbl_sub_product_category_idtbl_sub_product_category` = `sp`.`idtbl_sub_product_category`) 
             LEFT JOIN `tbl_group_category` AS `gp` ON (`p`.`tbl_group_category_idtbl_group_category` = `gp`.`idtbl_group_category`) 
             WHERE `s`.`status`=1 AND `p`.`status`=1 
             GROUP BY `s`.`tbl_product_idtbl_product` 
             ORDER BY `m`.`sequence`, `m`.`tbl_size_categories_idtbl_size_categories` ASC";

$resultstock = $conn->query($sqlstock);

if ($resultstock->num_rows > 0) {

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
            <h4 class="text-center">Stock Report from '.$fromdate.'</h4>
            <hr class="border-dark">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <table class="tablec">
                <thead>
                    <tr>
                        <th class="thc">Product</th>
                        <th class="thc">Size</th>
                        <th class="thc">Available Stock</th>
                        <th class="thc">Retail Price</th>
                        <th class="thc">Total Price</th>
                    </tr>  
                </thead>
                <tbody>';

    
    while ($rowresultstock = $resultstock->fetch_assoc()) {
        $total = $rowresultstock['retail'] * $rowresultstock['qty'];
        $html .= '
        <tr>
            <td class="tdc">' . $rowresultstock['product_name'] . '</td>  
            <td class="tdc">' . $rowresultstock['name'] . '</td>
            <td class="tdc">' . $rowresultstock['qty'] . '</td>
            <td class="tdc">' . number_format($rowresultstock['retail'], 2) . '</td>
            <td class="text-right">Rs.' . number_format($total, 2, '.', ',') . '</td>
        </tr>';
    }
    $html .= '
                </tbody>
            </table>
        </div>
    </div>
    </body>
    </html>';

    $dompdf->loadHtml($html); 
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Stock Report.pdf", ["Attachment" => 0]);

} else {
    echo "No records found for the selected date range.";
}
?>