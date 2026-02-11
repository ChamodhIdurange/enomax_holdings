<?php
session_start();
ini_set('memory_limit', '999M');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../vendor/autoload.php';
require_once('../connection/db.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$recordID=$_GET['record'];

function ConvertRupeeToText($amount) {
    $ones = array(
        0 => '',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen'
    );

    $tens = array(
        2 => 'twenty',
        3 => 'thirty',
        4 => 'forty',
        5 => 'fifty',
        6 => 'sixty',
        7 => 'seventy',
        8 => 'eighty',
        9 => 'ninety'
    );

    $amount = str_replace(',', '', $amount);
    $rupees = intval($amount);
    $cents = intval(round(($amount - $rupees) * 100));

    $words = '';

    $numberToWords = function($num) use (&$numberToWords, $ones, $tens) {
        $str = '';

        if ($num >= 1000000000) {
            $str .= $numberToWords(intval($num / 1000000000)) . ' billion ';
            $num %= 1000000000;
        }

        if ($num >= 1000000) {
            $str .= $numberToWords(intval($num / 1000000)) . ' million ';
            $num %= 1000000;
        }

        if ($num >= 1000) {
            $str .= $numberToWords(intval($num / 1000)) . ' thousand ';
            $num %= 1000;
        }

        if ($num >= 100) {
            $str .= $ones[intval($num / 100)] . ' hundred ';
            $num %= 100;
        }

        if ($num > 0) {
            if ($str !== '') {
                $str .= ' ';
            }

            if ($num < 20) {
                $str .= $ones[$num];
            } else {
                $str .= $tens[intval($num / 10)];
                if ($num % 10 > 0) {
                    $str .= '-' . $ones[$num % 10];
                }
            }
        }

        return trim($str);
    };

    if ($rupees > 0) {
        $words .= $numberToWords($rupees);
    }

    if ($cents > 0) {
        if ($rupees > 0) {
            $words .= ' and ';
        }
        $words .= $numberToWords($cents) . ' cents';
    }

    if ($words === '') {
        $words = 'zero';
    }

    return ucfirst(trim($words));
}  

// Fetch invoice HTML content
ob_start();

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`,`tbl_invoice`.`invtype`,`tbl_invoice`.`status`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`taxamount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`type`, `tbl_customer`.`vat_status`, `tbl_customer`.`vat_num`, `tbl_customer`.`name`, `tbl_customer`.`tax_cus_name`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area`, `tbl_customer`.`phone` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`idtbl_invoice`='$recordID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$vatStatus = $rowinvoiceinfo['vat_status'];
$invtype = $rowinvoiceinfo['invtype'];
$cusType = $rowinvoiceinfo['type'];
$status = $rowinvoiceinfo['status'];

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`trustqty`, `tbl_invoice_detail`.`trustreturnqty`, `tbl_invoice_detail`.`newprice`, `tbl_invoice_detail`.`refillprice`, `tbl_invoice_detail`.`emptyprice`, `tbl_invoice_detail`.`encustomer_newprice`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`encustomer_emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID'";
$resultinvoicedetail=$conn->query($sqlinvoicedetail);

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);

$method = null; 
$chequeNo = null;
$bankName = null;

$sqlpayment = "SELECT `tbl_invoice_payment_detail`.`method`,`tbl_invoice_payment_detail`.`chequeno`,`tbl_bank`.`bankname` FROM `tbl_invoice_payment_detail` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank` = `tbl_invoice_payment_detail`.`tbl_bank_idtbl_bank` LEFT JOIN `tbl_invoice_payment` ON `tbl_invoice_payment`.`idtbl_invoice_payment` = `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment`.`idtbl_invoice_payment` WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` = $recordID";

$resultpayment = $conn->query($sqlpayment);

if ($resultpayment->num_rows > 0) {
    $rowpaymentinfo = $resultpayment->fetch_assoc();

    $method = $rowpaymentinfo['method'];

    if ($method == 1) {
        $paymentType = "Cash";
    } elseif ($method == 2) {
        $paymentType = "Credit";
    } elseif ($method == 3) {
        $paymentType = "Cheque";
    } else {
        $paymentType = "Unknown";
    }

    $chequeNo = $rowpaymentinfo['chequeno'];
    $bankName = $rowpaymentinfo['bankname'];
}

$vatValue = null;
if ($resultvat) {
    $rowvat = $resultvat->fetch_assoc();

    if ($rowvat) {
        $vatValue = $rowvat['vat'];
    }
}

$sqlpaymethod = "SELECT 
    GROUP_CONCAT(
        CASE `tbl_invoice_payment_detail`.`method`
            WHEN 1 THEN 'Cash'
            WHEN 2 THEN 'Bank / Cheque'
            WHEN 3 THEN 'Online'
            ELSE 'Unknown'
        END
    ) AS payment_methods
FROM `tbl_invoice_payment_detail` 
LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` 
    ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment` 
WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` = '$recordID' 
  AND `tbl_invoice_payment_detail`.`status` = 1";
$resultpaymethod = $conn->query($sqlpaymethod);
$rowpaymethod = $resultpaymethod->fetch_assoc();

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$html='';
if ($vatStatus == 0) {
    $invoiceno = 'INT-' . $rowinvoiceinfo['idtbl_invoice'];
    $invoiceDetails = [];
    while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
        $invoiceDetails[] = $rowinvoicedetail;
    }
    $html.='
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>LAUGFS Gas PLC - TAX Invoice INT'.$rowinvoiceinfo['idtbl_invoice'].'</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                margin: 0px;
            }
        </style>
    </head>

    <body>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: right;padding-right: 20px;" width="40%">
                    <img src="https://aws.erav.lk/ansengascrm/images/logoprint.png" alt="LAUGFS Gas PLC" width="100">
                </td>
                <td>
                    <h2 style="font-size: 16px; margin-top: 5px; margin-bottom: 5px;">ANSEN GAS DISTRIBUTORS (PVT) LTD</h2>
                    65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka<br>
                    Tel: 0312 235 050 | Email: info@ansengas.lk<br>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width: 50%;border-collapse: collapse;margin-top: 20px;">
                        <tr>
                            <td width="40%">Invoice Date</td>
                            <td width="10%">:</td>
                            <td>'.$rowinvoiceinfo['date'].'</td>
                        </tr>
                        <tr>
                            <td width="40%">Invoice No</td>
                            <td width="10%">:</td>
                            <td>INV-'.$rowinvoiceinfo['idtbl_invoice'].'</td>
                        </tr>
                        <tr>
                            <td width="40%">Customer Name</td>
                            <td width="10%">:</td>
                            <td>'.$rowinvoiceinfo['name'].'</td>
                        </tr>
                        <tr>
                            <td width="40%">Customer Address</td>
                            <td width="10%">:</td>
                            <td>'.$rowinvoiceinfo['address'].'</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">';
                    if ($invtype == 1) {$html.='<h3 class="text-center">Free Issue Note</h3>';}
                    $html.='<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                        <tr>
                            <th style="border: 1px solid black; padding: 5px;">Product</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">New</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Refill</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Empty</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Trust</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Trust Return</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Sale Price</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Refill Price</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Empty Price</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Total</th>
                        </tr>';
                        foreach ($invoiceDetails as $rowinvoicedetail) {
                            // Check if cusType is equal to 1
                            if ($cusType == 1) {
                                $vatNew = $rowinvoicedetail['encustomer_newprice'] * $rowvat['vat'] / 100;
                                $vatRefill = $rowinvoicedetail['encustomer_refillprice'] * $rowvat['vat'] / 100;
                                $vatEmpty = $rowinvoicedetail['encustomer_emptyprice'] * $rowvat['vat'] / 100;
                            } else {
                                $vatNew = $rowvat['vat'] * $rowinvoicedetail['newprice'] / 100;
                                $vatRefill = $rowvat['vat'] * $rowinvoicedetail['refillprice'] / 100;
                                $vatEmpty = $rowvat['vat'] * $rowinvoicedetail['emptyprice'] / 100;
                            }

                            // Calculate total prices including VAT
                            $totalWithVAT = number_format(
                                ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice'] + $vatNew))
                                + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                                + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice'] + $vatEmpty))
                                + ($rowinvoicedetail['trustqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                                + ($rowinvoicedetail['trustreturnqty'] * 0),
                                2
                            );

                            $totalWithVATencus = number_format(
                                ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['encustomer_newprice'] + $vatNew))
                                + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['encustomer_refillprice'] + $vatRefill))
                                + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty))
                                + ($rowinvoicedetail['trustqty'] * ($rowinvoicedetail['encustomer_refillprice'] + $vatRefill))
                                + ($rowinvoicedetail['trustreturnqty'] * 0),
                                2
                            );
                            
                            $html.='
                            <tr>
                                <td style="border: 1px solid black; padding: 5px;">'.$rowinvoicedetail['product_name'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['newqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['refillqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['emptyqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['trustqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['trustreturnqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px;">' . (($invtype == 1) ? '0.00' : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] + $vatNew : ($rowinvoicedetail['newprice'] + $vatNew), 2)) . '</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: right;">' . (($invtype == 1) ? '0.00' : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] + $vatRefill : ($rowinvoicedetail['refillprice'] + $vatRefill), 2)) . '</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: right;">' . (($invtype == 1) ? '0.00' : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty : ($rowinvoicedetail['emptyprice'] + $vatEmpty), 2)) . '</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: right;">' . (($invtype == 1) ? '0.00' : (($cusType == 1) ? $totalWithVATencus : $totalWithVAT)).'</td>
                            </tr>';
                        }
                        if ($invtype == 1) {
                            $html.='<tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Total</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">VAT</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Net Total</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">0.00</th>
                                </tr>
                            ';
                        }
                        else{
                            if ($vatStatus == 1) {
                                $html.='<tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Total</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">' . number_format($rowinvoiceinfo['total'], 2) . '</th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">VAT ('.$rowvat['vat'].'%)</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">' . number_format($rowinvoiceinfo['taxamount'], 2) . '</th>
                                </tr>';
                            }
                            $html.='<tr>
                                <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Net Total</th>
                                <th style="border: 1px solid black; padding: 5px; text-align: right;">' . number_format($rowinvoiceinfo['nettotal'], 2) . '</th>
                            </tr>';
                        }
                    $html.='</table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px;">
                    <h4>Payment Mode</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Cash</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;font-family: \'DejaVu Sans\', sans-serif;">'.($method == 1 ? '&#10004;' : '').'</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Credit</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;font-family: \'DejaVu Sans\', sans-serif;">'.($method == 3 ? '&#10004;' : '').'</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Cheque</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;font-family: \'DejaVu Sans\', sans-serif;" width="20%">'.($method == 2 ? '&#10004;' : '').'</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">No</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">'.$chequeNo.'</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Bank</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">'.$bankName.'</th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 5px; text-align: center;">
                                <br><br><br>
                                _______________________<br>
                                Sig. of Driver
                            </td>
                            <td style="padding: 5px; text-align: center;">
                                <br><br><br>
                                _______________________<br>
                               Company Seal
                            </td>
                            <td style="padding: 5px; text-align: center;">
                                <br><br><br>
                                _______________________<br>
                                Sig. of Customer
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 5px; text-align: center;">';
                if ($method == 1) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/paid.png" width="150" height="150">';
                elseif ($method == 2) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/received.png" width="150" height="150">';
                elseif ($method == 3) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/credit.png" width="150" height="150">';
                elseif ($status == 3) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/cancel.png" width="150" height="150">';
                endif;
                $html.='</td>
                <td style="width: 50%; padding: 5px; text-align: center;">
                    <br><br><br>
                    _______________________<br>
                    Name & Sig. of Authorized Person
                    <br><br><br>
                    _______________________<br>
                    NIC No
                </td>
            </tr>
        </table>
    </body>
    </html>
    ';
}
else{
    $invoiceno = 'AGT' . $rowinvoiceinfo['tax_invoice_num'];
    $invoiceDetails = [];
    while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
        $invoiceDetails[] = $rowinvoicedetail;
    }

    $html.='
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>LAUGFS Gas PLC - TAX Invoice AGT'.$rowinvoiceinfo['tax_invoice_num'].'</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                margin: 0px;
            }
        </style>
    </head>

    <body>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td>&nbsp;</td>
                <td width="25%">
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                        <tr>
                            <td style="border: 1px solid black; text-align: center;"><h3 style="margin-top: 5px;margin-bottom: 5px;">Tax Invoice</h3></td>
                        </tr>
                    </table>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;margin-top: 10px;border-collapse: separate; border-spacing: 15px 0;">
                        <tr>
                            <td width="50%" style="border: 1px solid black; padding: 5px;vertical-align: top;">
                                <strong>Date of Invoice:</strong> '.$rowinvoiceinfo['date'].'
                            </td>
                            <td style="border: 1px solid black; padding: 5px;vertical-align: top;">
                                <strong>Tax Invoice No:</strong> AGT'.$rowinvoiceinfo['tax_invoice_num'].'
                            </td>                            
                        </tr>                    
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;margin-top: 10px;border-collapse: separate; border-spacing: 15px 0;">
                        <tr>
                            <td width="50%" style="border: 1px solid black; padding: 5px;vertical-align: top;">
                                <strong>Supplier\'s TIN:</strong> 102575474-7000<br>
                                <strong>Supplier\'s Name:</strong> ANSEN GAS DISTRIBUTORS (PVT) LTD<br>
                                <strong>Address:</strong> 65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka<br>
                                <strong>Phone:</strong> 0312 235 050
                            </td>
                            <td style="border: 1px solid black; padding: 5px;vertical-align: top;">
                                <strong>Purchaser\'s TIN:</strong> '.$rowinvoiceinfo['vat_num'].'<br>
                                <strong>Purchaser\'s Name:</strong> '.$rowinvoiceinfo['tax_cus_name'].'<br>
                                <strong>Address:</strong> '.$rowinvoiceinfo['address'].'<br>
                                <strong>Phone:</strong> '.$rowinvoiceinfo['phone'].'
                            </td>                            
                        </tr>                    
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;margin-top: 10px;border-collapse: separate; border-spacing: 15px 0;">
                        <tr>
                            <td width="50%" style="border: 1px solid black; padding: 5px;vertical-align: top;">
                                <strong>Date of Delivery:</strong> '.$rowinvoiceinfo['date'].'
                            </td>
                            <td style="border: 1px solid black; padding: 5px;vertical-align: top;">
                                <strong>Place of Supply:</strong> 
                            </td>                            
                        </tr>                    
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;margin-top: 10px;border-collapse: separate; border-spacing: 15px 0;">
                        <tr>
                            <td style="border: 1px solid black; padding: 5px;vertical-align: top;height: 75px;">
                                <strong>Additional Information if any: </strong> 
                            </td>                        
                        </tr>                    
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;border-collapse: separate; border-spacing: 15px 0;">
                        <tr>
                            <td>
                                <table style="width: 100%; border-collapse: collapse;border: 1px solid black; margin-top: 10px;">
                                    <tr>
                                        <th style="border: 1px solid black; padding: 5px;">Reference</th>
                                        <th style="border: 1px solid black; padding: 5px;">Description of Goods or Services</th>
                                        <th style="border: 1px solid black; padding: 5px;text-align: center;">Quantity</th>
                                        <th style="border: 1px solid black; padding: 5px;text-align: right;">Unit Price</th>
                                        <th style="border: 1px solid black; padding: 5px;text-align: right;">Amount Excluding VAT (Rs.)</th>
                                    </tr>
                                    ';
                                    $invoiceproducts = array();
                                    $i=1;
                                    foreach ($invoiceDetails as $rowinvoicedetail) {
                                        // Check if cusType is equal to 1
                                        if ($cusType == 1) {
                                            $vatNew = $rowinvoicedetail['encustomer_newprice'] * $rowvat['vat'] / 100;
                                            $vatRefill = $rowinvoicedetail['encustomer_refillprice'] * $rowvat['vat'] / 100;
                                            $vatEmpty = $rowinvoicedetail['encustomer_emptyprice'] * $rowvat['vat'] / 100;
                                        } else {
                                            $vatNew = $rowvat['vat'] * $rowinvoicedetail['newprice'] / 100;
                                            $vatRefill = $rowvat['vat'] * $rowinvoicedetail['refillprice'] / 100;
                                            $vatEmpty = $rowvat['vat'] * $rowinvoicedetail['emptyprice'] / 100;
                                        }

                                        // Calculate total prices including VAT
                                        $totalWithVAT = number_format(
                                            ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice'] + $vatNew))
                                            + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                                            + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice'] + $vatEmpty))
                                            + ($rowinvoicedetail['trustqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                                            + ($rowinvoicedetail['trustreturnqty'] * 0),
                                            2
                                        );

                                        $totalWithVATencus = number_format(
                                            ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['encustomer_newprice'] + $vatNew))
                                            + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['encustomer_refillprice'] + $vatRefill))
                                            + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty))
                                            + ($rowinvoicedetail['trustqty'] * ($rowinvoicedetail['encustomer_refillprice'] + $vatRefill))
                                            + ($rowinvoicedetail['trustreturnqty'] * 0),
                                            2
                                        );

                                        if($rowinvoicedetail['newqty']>0) {
                                            $obj = new stdClass();
                                            $obj->reference = $i;
                                            $obj->description = $rowinvoicedetail['product_name'].' - New';
                                            $obj->quantity = $rowinvoicedetail['newqty'];
                                            $obj->unitprice = $cusType == 1 ? number_format(($rowinvoicedetail['encustomer_newprice']+$vatNew), 2) : number_format(($rowinvoicedetail['newprice']+$vatNew), 2);
                                            $obj->amount = $cusType == 1 ? number_format($rowinvoicedetail['newqty'] * ($rowinvoicedetail['encustomer_newprice']+$vatNew), 2) : number_format($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice']+$vatNew), 2);
                                            array_push($invoiceproducts, $obj);
                                        }
                                        if($rowinvoicedetail['refillqty']>0) {
                                            $obj = new stdClass();
                                            $obj->reference = $i;
                                            $obj->description = $rowinvoicedetail['product_name'].' - Refill';
                                            $obj->quantity = $rowinvoicedetail['refillqty'];
                                            $obj->unitprice = $cusType == 1 ? number_format(($rowinvoicedetail['encustomer_refillprice']+$vatRefill), 2) : number_format(($rowinvoicedetail['refillprice']+$vatRefill), 2);
                                            $obj->amount = $cusType == 1 ? number_format($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['encustomer_refillprice']+$vatRefill), 2) : number_format($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice']+$vatRefill), 2);
                                            array_push($invoiceproducts, $obj);
                                        }
                                        if($rowinvoicedetail['emptyqty']>0) {
                                            $obj = new stdClass();
                                            $obj->reference = $i;
                                            $obj->description = $rowinvoicedetail['product_name'].' - Empty';
                                            $obj->quantity = $rowinvoicedetail['emptyqty'];
                                            $obj->unitprice = $cusType == 1 ? number_format(($rowinvoicedetail['encustomer_emptyprice']+$vatEmpty), 2) : number_format(($rowinvoicedetail['emptyprice']+$vatEmpty), 2);
                                            $obj->amount = $cusType == 1 ? number_format($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['encustomer_emptyprice']+$vatEmpty), 2) : number_format($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice']+$vatEmpty), 2);
                                            array_push($invoiceproducts, $obj);
                                        }
                                        $i++;
                                    }
                                    foreach ($invoiceproducts as $product) {
                                        $html.='
                                        <tr>
                                            <td style="border: 1px solid black; padding: 5px;">'.$product->reference.'</td>
                                            <td style="border: 1px solid black; padding: 5px;">'.$product->description.'</td>
                                            <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$product->quantity.'</td>
                                            <td style="border: 1px solid black; padding: 5px; text-align: right;">'.$product->unitprice.'</td>
                                            <td style="border: 1px solid black; padding: 5px; text-align: right;">'.$product->amount.'</td>
                                        </tr>
                                        ';
                                    }
                                    $html.='<tr>
                                        <td colspan="4" style="border: 1px solid black; padding: 5px;">Total Value of Supply:</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;"><strong>'.number_format($rowinvoiceinfo['total'], 2).'</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="border: 1px solid black; padding: 5px;">VAT Amount (Total Value of Supply @ '.$rowvat['vat'].'%)	</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;"><strong>'.number_format($rowinvoiceinfo['taxamount'], 2).'</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="border: 1px solid black; padding: 5px;">Total Amount including VAT:</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;"><strong>'.number_format($rowinvoiceinfo['nettotal'], 2).'</strong></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;margin-top: 10px;border-collapse: separate; border-spacing: 15px 0;">
                        <tr>
                            <td style="border: 1px solid black; padding: 5px;vertical-align: top;">
                                <strong>Total Amount in words: </strong> '.ConvertRupeeToText($rowinvoiceinfo['nettotal']).'
                            </td>                        
                        </tr>                    
                        <tr>
                            <td style="border: 1px solid black; padding: 5px;vertical-align: top;border-top: none;">
                                <strong>Mode of Payment: </strong> '.$rowpaymethod['payment_methods'].'
                            </td>                        
                        </tr>                    
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    ';
}

// echo $html;
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("ansen_gas_invoice_" . $invoiceno . ".pdf", ["Attachment" => false]);

// Close database connection
mysqli_close($conn);
?>