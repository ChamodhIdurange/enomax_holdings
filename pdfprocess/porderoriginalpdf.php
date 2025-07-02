<?php
session_start();
require_once('../connection/db.php');
require_once '../vendor/autoload.php'; // Adjust the path as necessary

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

$today = date('Y-m-d');
$today2 = date('Y/m');
$last_two_digits = substr($today2, 2);
$recordID = $_GET['id'];

$empty = 'null';
$fulltot = 0;
$discount = 0;
$totaloutstanding = 0;
$fulloutstanding = 0;
$totalpayment = 0;
$net_total = 0;
$newtemp = 0;


$sqlporderinfo = "SELECT `o`.`idtbl_original_customer_order`, `o`.`remark`, `o`.`tbl_customer_order_idtblcustomer_order`, `o`.`date`, `o`.`total`, `l`.`idtbl_locations`, `l`.`locationname`, `c`.`customer`, `c`.`address`, `c`.`phone` AS 'customerphone', `e`.`name` AS `saleref`, `e`.`phone`, `a`.`area`, `u`.`name` as `username`, `o`.`tbl_customer_idtbl_customer`, `o`.`cuspono` FROM `tbl_original_customer_order` AS `o` LEFT JOIN `tbl_original_customer_order_detail` AS `od` ON `o`.`idtbl_original_customer_order`=`od`.`tbl_original_customer_order_idtbl_original_customer_order` LEFT JOIN `tbl_customer` AS `c` ON (`c`.`idtbl_customer` = `o`.`tbl_customer_idtbl_customer`) LEFT JOIN `tbl_locations` AS `l` ON (`l`.`idtbl_locations` = `o`.`tbl_locations_idtbl_locations`) LEFT JOIN `tbl_employee` AS `e` ON `e`.`idtbl_employee`=`o`.`tbl_employee_idtbl_employee` LEFT JOIN `tbl_area` AS `a` ON `a`.`idtbl_area`=`o`.`tbl_area_idtbl_area` LEFT JOIN `tbl_user` AS `u` ON `u`.`idtbl_user`=`o`.`tbl_user_idtbl_user` WHERE `o`.`status`=1 AND `o`.`tbl_customer_order_idtblcustomer_order`='$recordID'";
$resultporderinfo = $conn->query($sqlporderinfo);
$rowporderinfo = $resultporderinfo->fetch_assoc();

$originalId = $rowporderinfo['idtbl_original_customer_order'];
$customerID = $rowporderinfo['tbl_customer_idtbl_customer'];
$customerPhone = $rowporderinfo['customerphone'];
$porderDate = $rowporderinfo['date'];
$customername = $rowporderinfo['customer'];
$location = $rowporderinfo['locationname'];
$customeraddress = $rowporderinfo['address'];
$poderId = $rowporderinfo['tbl_customer_order_idtblcustomer_order'];
$remark = $rowporderinfo['remark'];


$sqlporderdetail = "SELECT `p`.`product_name`, `p`.`product_code`, `p`.`idtbl_product`, `d`.`qty`, `d`.`saleprice` FROM `tbl_original_customer_order_detail` AS `d` LEFT JOIN `tbl_product` AS `p` ON `p`.`idtbl_product`=`d`.`tbl_product_idtbl_product` WHERE `d`.`tbl_original_customer_order_idtbl_original_customer_order`='$originalId' AND `d`.`status`=1";
$resultporderdetail = $conn->query($sqlporderdetail);

$html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ENOMAX Holdings (PVT) LTD</title>
        <style>
            * {
            font-size: 10px;
            margin: 0.2px;
            font-family: "San-Serif", sans-serif;
            }
            #detailtable {
                width: 100%;
                border-collapse: collapse;
                padding-left: 1cm;
                padding-right: 1cm;
                padding-top: 0.2cm;
            }
            #detailth, #detailtd {
                border: 1px solid #ddd;
                padding: 5px;
            }
            #detailth {
                background-color: #f2f2f2;
                text-align: left;
            }
            .page-total {
                background-color: #f9f9f9;
                font-weight: bold;
            }

            #tablefooter {
                width: 100%;
            }

            /* Style for the customer details section */
            .leftboxtop {
                padding-left: 0.3cm;
            }
            .rightboxtop {
                padding-right: 0.3cm;
            }

            /* Table header styling */
            th {
                background-color: #f2f2f2;
                font-size: 12px;
                text-align: left;
                padding: 5px;
            }

            /* Table data cell styling */
            td {
                padding: 5px;
                vertical-align: top;
            }
            .tdheader {
                padding: 5px;
                vertical-align: top;
                font-size: 20px;

            }

            /* Specific alignment for certain cells */
            .left-align {
                text-align: left;
            }

            .right-align {
                text-align: right;
            }

            .center-align {
                text-align: center;
            }

            /* Custom spacing */
            .spacer {
                height: 1.8cm;
            }
           
        </style>
    </head>
    <body style="height:14cm">

    <header>
        <table border="0" width="100%">
            <tr>
                <td colspan="3" height="1.8cm"></td>
            </tr>
            <tr>
                <td class="leftboxtop" width="100%">
                    <table border="0" width="100%" style="margin-top:-43; padding-left:0.3cm;">
                        <tr>
                            <td>
                                <h3 style="font-weight: bold; font-size: 20px; margin: 0;">ENOMAX HOLDINGS (PVT) LTD</h3>
                                <h4 style="font-size: 16px; margin-top: 0.2cm;">No.46, Garden City, Minuwangoda Road, Ja-ela.</h4>
								<h4>Tel: 011 3468568</h4>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table border="0" width="100%">
            <tr>
                <td colspan="3" height="1.8cm"></td>
            </tr>
            <tr>
                <td class="leftboxtop" width="10cm">
                    <table border="0" width="100%" style="margin-top:-70; padding-left:0.3cm;">
                        <tr>
                            <th>Customer Details - '. $customerID .'</th>
                        </tr>
                        <tr>
                            <td>'. $customername . '<br><br>' . $customeraddress . '<br><br>Tel : ' . $customerPhone . '<br><br>Date : ' . $porderDate . '</td>
                        </tr>
                    </table>
                </td>
                <td style="padding-left:100px;" width="8cm">
                    <table width="100%" height="100%" style="margin-top:-70;" border="0">
                        <tr>
                            <th align="center" colspan="2">Purchase Order Details - '. $poderId .'</th>
                        </tr>
                        <tr><td align="left" style="font-weight: bold;">LOCATION </td><td>' . $location . ' </td></tr>
                        <tr><td align="left" style="font-weight: bold;">EMPLOYEE </td><td>' . $rowporderinfo['saleref'] . ' </td></tr>
                        <tr><td align="left" style="font-weight: bold;">CONTACT </td><td>' . $rowporderinfo['phone'] . ' </td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </header>

    <main>
        <div class="" style="margin-top:15px;">
            <table width="100%" style="padding-left:1cm; padding-right:1cm; padding-top:0.2cm;" id="detailtable">
            <thead>
                <tr>
                    <th style="width:2cm;" id="detailth">#</th>
                    <th style="width:1.5cm;" id="detailth">Code</th>
                    <th style="width:7.5cm;" id="detailth">Product Name</th>
                    <th style="width:1.5cm;" id="detailth" align="right">Quantity</th>
                    <th style="width:2.5cm;" id="detailth" align="right">Sale Price</th>
                    <th style="width:2.6cm;" id="detailth" align="right">Total</th>
                </tr>
            </thead>
            ';
            $rowCount = mysqli_num_rows($resultporderdetail);
            $count = 0;
            $count1 = 0;

            while ($rowporderdetail = $resultporderdetail->fetch_assoc()) {
                $totnew = $rowporderdetail['qty'] * $rowporderdetail['saleprice'];
                $fulltot += $totnew;
                $count = $count + 1;
                $count1++;
                $html .= '
                    <tr>
                        <td style="width:2cm;" id="detailtd">' . $rowporderdetail['idtbl_product'] . '</td>
                        <td style="width:1.5cm;" id="detailtd">' . $rowporderdetail['product_code'] . '</td>
                        <td style="width:7.5cm;" id="detailtd">' . $rowporderdetail['product_name'] . '</td>
                        <td style="width:1.5cm;" id="detailtd" align="right">' . $rowporderdetail['qty'] . '</td>
                        <td style="width:2.5cm;" id="detailtd" align="right">' . number_format($rowporderdetail['saleprice'], 2) . '</td>
                        <td style="width:2.6cm;" id="detailtd" align="right">' . number_format(($rowporderdetail['saleprice'] * $rowporderdetail['qty']), 2) . '</td>
                    </tr>
                ';
                $temptotal = $rowporderdetail['qty'] * $rowporderdetail['saleprice'];
                $newtemp += $temptotal;
                if ($count1 % 28 == 0) {
                    $html .= '
                        <tr>
                            <td colspan="5">This page Total Showing here. See the Next page Thank You</td>
                            <td style="width:2.6cm;" align="right">' . number_format($newtemp, 2) . '</td>
                        </tr>
                    ';
                    $newtemp = 0;
                }
            }
            $discount = $fulltot - $rowporderinfo['total'];
            $html .= '
            </table> 
            ';

            if ($resultporderdetail->num_rows == $count) {
                $html .= '
                    <footer>
                        <div style="">
                            <table border="0" width="100%">
                                <tr>
                                    <td class="leftboxtop" width="10cm">
                                        <table border="0" width="100%" style="padding-left:0.5cm;">
                                            <tr>
                                                <th>REMARKS</th>
                                            </tr>
                                            <tr>
                                                <td>'. $remark . '</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="8cm">
                                        <table border="0" id="tablefooter" style="margin-right:35px"> 
                                        ';
                                            $discount = $fulltot - $rowporderinfo["total"];
                                            $net_total = $fulltot - $discount;

                                            $html .= '
                                            <tr>
                                                <td align="right" style="font-weight: bold;">Net Total:</td>
                                                <td align="right">' . number_format($fulltot, 2) . '</td>
                                            </tr>
                                            <tr>
                                                <td align="right" style="font-weight: bold;">Discount:</td>
                                                <td align="right" style="padding-top:0.2cm;">' . number_format($discount, 2) . '</td>
                                            </tr>
                                            <tr>
                                                <td align="right" style="font-weight: bold;">Total:</td>
                                                <td align="right" style="padding-top:0.2cm;">' . number_format($net_total, 2) . '</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%">
								<tr>
									<td colspan="2">Received the above goods in good condition</td>
									<td colspan="4">&nbsp;</td>
								</tr>
								<tr>
									<td>Received By</td>
									<td>..............</td>
									<td>&nbsp;</td>
									<td align="center">..............</td>
									<td align="center">..............</td>
									<td align="center">..............</td>
								</tr>
								<tr>
									<td>Date &amp; Time</td>
									<td>..............</td>
									<td>&nbsp;</td>
									<td align="center">Prepared By</td>
									<td align="center">Authorized By</td>
									<td align="center">Taken Out By</td>
								</tr>
							</table>
                        </div>
                    </footer>';
            }
            $html .= '  
        </div>
        
    </main>
    </body>
    </html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('21.5cm', '27.5cm', 'portrait');
$dompdf->render();
$dompdf->stream("Test.pdf", ["Attachment" => 0]);
