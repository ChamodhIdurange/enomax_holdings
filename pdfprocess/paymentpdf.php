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

$invoiceId=$_GET['invoiceId'];

$sqlinvoice="SELECT * FROM `tbl_invoice` WHERE `idtbl_invoice`='$invoiceId'";
$resultinvoice=$conn->query($sqlinvoice);
$rowinvoice=$resultinvoice->fetch_assoc();
$nettotal = $rowinvoice['nettotal'];
$total = $rowinvoice['total'];
$discount = $rowinvoice['discount'];
$invoiceno = $rowinvoice['invoiceno'];



$sqlpayment="SELECT SUM(`ih`.`payamount`) AS 'paymentmade' FROM `tbl_invoice_payment` AS `ip` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `ih` ON (`ip`.`idtbl_invoice_payment` = `ih`.`tbl_invoice_payment_idtbl_invoice_payment`) WHERE `ih`.`tbl_invoice_idtbl_invoice`='$invoiceId' GROUP BY `ih`.`tbl_invoice_idtbl_invoice`";
$resultpayment=$conn->query($sqlpayment);
$rowpayment=$resultpayment->fetch_assoc();
$paymentmade = $rowpayment['paymentmade'];

$sqlpaymentbank="SELECT `id`.`method`, `id`.`amount`, `id`.`receiptno`, `id`.`chequeno` FROM `tbl_invoice_payment_detail` AS `id` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `ih` ON (`id`.`tbl_invoice_payment_idtbl_invoice_payment` = `ih`.`tbl_invoice_payment_idtbl_invoice_payment`) WHERE `tbl_invoice_idtbl_invoice`='$invoiceId'";
$resultpaymentbank=$conn->query($sqlpaymentbank);
// echo $sqlpaymentbank;
$html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ENOMAX Holdings (PVT) LTD</title>
        <style>
            body {
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans",
                    sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                line-height: 1.5;
            }
            .tg  {border-collapse:collapse;border-spacing:0;}
            .tg td{font-family:Arial, sans-serif;font-size:14px;padding:5px 10px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
            .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:5px 10px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
            .tg .tg-btmp{font-weight:bold;color:#000;text-align:left;vertical-align:top}
            .tg .tg-0lax{text-align:left;vertical-align:top}

            .receipt-header {
                font-family: Arial, sans-serif;
                margin-bottom: 20px;
                text-align: right; /* Center text for h4 and p */
                position: relative; /* Allows absolute positioning of the head-label */
            }

            .head-label {
                background-color: #000;
                color: #FFF;
                padding: 5px 15px;
                border-radius: 5px;
                width: 160px;
                text-align: center;
                position: absolute; /* Position relative to .receipt-header */
                top: -30px; /* Move up to be above h4 */
                right: 0; /* Align to the right edge */
            }

            h4 {
                margin: 30px 0 0; /* Adjust top margin to clear the label */
                font-weight: bold;
            }

            p {
                margin: 0;
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
        <table border="0" width="100%">
            <tr>
                <td style="vertical-align: bottom;padding-bottom: 17px;">Invoice No: '.$invoiceno.'</td>
                <td style="text-align: right;">
                    <div class="receipt-header">
                        <div class="header-content">
                            <div class="head-label">PAYMENT RECEIPT</div>
                        </div>
                        <h4>ENOMAX Holdings (PVT) LTD</h4>
                        <p>
                            
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border-color: #000;margin-top:5px; margin-bottom:5px;">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="tg" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice No</th>
                                <th style="text-align: right">Invoice Amount</th>
                                <th style="text-align: right">Discount</th>
                                <th style="text-align: right">Payment</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $i=1;
                            $html.='<tr>
                                <td>'.$i.'</td>
                                <td>'.$invoiceno.'</td>
                                <td style="text-align: right">';

                                    $html.=$nettotal;
                                $html.='</td>
                                <td style="text-align: right">'.$discount.'</td>
                                <td style="text-align: right">';$paymentdone = $total- $discount; $html.=number_format($paymentdone,2).'</td>
                            </tr>';
                        $html.='</tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="50%" style="vertical-align: top;">
                    <div style="margin-bottom: 15px; margin-top: 15px;">Payment Methods</div>
                    <table class="tg" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Receipt</th>
                                <th>Cheque No</th>
                                <th style="text-align: right">Payment</th>
                            </tr>
                        </thead>
                        <tbody>'; 
                        while($rowpaymentbank=$resultpaymentbank->fetch_assoc()){
                            $html.='<tr>
                                <td>';
                                if($rowpaymentbank['method']==1){
                                    $html.='Cash';
                                }else if($rowpaymentbank['method']==2){
                                    $html.='Cheque';
                                }else if($rowpaymentbank['method']==3){
                                    $html.='Credit Note';
                                }
                            $html.='</td>
                                <td>'.$rowpaymentbank['receiptno'].'</td>
                                <td>'.$rowpaymentbank['chequeno'].'</td>
                                <td style="text-align: right">'.number_format($rowpaymentbank['amount'], 2).'</td>
                            </tr>';
                        }
                        $html.='<tbody>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table width="100%">
                        <tr>
                            <td width="65%" style="text-align: right">Payment Made</td>
                            <td style="text-align: right">Rs. '.number_format($paymentmade, 2).'</td>
                        </tr>
                        <tr>
                            <td width="65%" style="text-align: right">Balance</td>
                            <td style="text-align: right">Rs. '.number_format($nettotal - $paymentmade, 2).'</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';
// echo $html;
$dompdf->loadHtml($html);
// $dompdf->setPaper('21.5cm', '27.5cm', 'portrait');
$dompdf->render();
$dompdf->stream("Test.pdf", ["Attachment" => 0]);

