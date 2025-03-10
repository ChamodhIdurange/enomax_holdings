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

$validfrom = $_GET['validfrom'] ?? '';
$validto = $_GET['validto'] ?? '';
$accountID = $_GET['selectedAccount'] ?? '';
$searchType = $_GET['searchType'] ?? '';

// Validate date range
if (empty($validfrom) || empty($validto)) {
    echo "<div style=\"color: red; font-size:20px;\">Invalid date range provided.</div>";
    exit();
}

if ($searchType == '2') { 
    if ($accountID > 0) {
        // Fetch account information
        $sqlaccountinfo = "SELECT `u`.`account`, `u`.`accountno`, `u`.`idtbl_account`, `ub`.`bankname` 
                           FROM `tbl_account` AS `u` 
                           LEFT JOIN `tbl_bank` AS `ub` ON `u`.`tbl_bank_idtbl_bank` = `ub`.`idtbl_bank` 
                           WHERE `u`.`idtbl_account` = ?";
        
        $stmt = $conn->prepare($sqlaccountinfo);
        $stmt->bind_param("i", $accountID);
        $stmt->execute();
        $accountInfoResult = $stmt->get_result();
        $accountInfo = $accountInfoResult->fetch_assoc();

        if (!$accountInfo) {
            echo "<div style=\"color: red; font-size:20px;\">No Account Information Found</div>";
            exit();
        }

        // Fetch expenses information
        $sqlexpensesinfo = "SELECT `amount` AS `expencesamount`, `narration`, DATE(`insertdatetime`) AS `expencesdate` 
                            FROM `tbl_pettycash_expenses` 
                            WHERE `tbl_account_petty_cash_account` = ? 
                            AND DATE(`insertdatetime`) BETWEEN ? AND ?
                            ORDER BY DATE(`insertdatetime`) ASC";
        
        $stmt = $conn->prepare($sqlexpensesinfo);
        $stmt->bind_param("iss", $accountID, $validfrom, $validto);
        $stmt->execute();
        $expensesResult = $stmt->get_result();

        // Fetch reimbursements information
        $sqlreimburseinfo = "SELECT `amount` AS `reimburseamount`, DATE(`insertdatetime`) AS `reimbursedate` 
                             FROM `tbl_pettycash_reimburse` 
                             WHERE `tbl_account_petty_cash_account` = ? 
                             AND DATE(`insertdatetime`) BETWEEN ? AND ?
                             ORDER BY DATE(`insertdatetime`) ASC";
        
        $stmt = $conn->prepare($sqlreimburseinfo);
        $stmt->bind_param("iss", $accountID, $validfrom, $validto);
        $stmt->execute();
        $reimburseResult = $stmt->get_result();

        // Calculate total cash in and cash out
        $totalCashIn = 0;
        $totalCashOut = 0;

        $rows = [];

        // Collect expense data
        while ($expRow = $expensesResult->fetch_assoc()) {
            $rows[] = [
                'date' => $expRow['expencesdate'],
                'cashin' => '-',
                'cashout' => $expRow['expencesamount'],
            ];
            $totalCashOut += $expRow['expencesamount'];
        }

        // Collect reimbursement data
        while ($reimburseRow = $reimburseResult->fetch_assoc()) {
            $rows[] = [
                'date' => $reimburseRow['reimbursedate'],
                'cashin' => $reimburseRow['reimburseamount'],
                'cashout' => '-',
            ];
            $totalCashIn += $reimburseRow['reimburseamount'];
        }

        // Sort rows by date
        usort($rows, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Fetch balance
        $sqlbalance = "SELECT `balance` FROM `tbl_pettycash_account_balance` WHERE `tbl_account_petty_cash_account` = ?";
        $stmt = $conn->prepare($sqlbalance);
        $stmt->bind_param("i", $accountID);
        $stmt->execute();
        $balanceResult = $stmt->get_result();
        $balance = $balanceResult->fetch_assoc()['balance'] ?? 0;

        $html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Account Statement</title>
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
            <div style="font-family: Arial, sans-serif;">
                <h3 style="text-align: center;">Account Statement</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="text-align: left; width: 50%;"><strong>Account:</strong> ' . htmlspecialchars($accountInfo['account']) . '</td>
                        <td style="text-align: left; width: 50%;"><strong>Account No:</strong> ' . htmlspecialchars($accountInfo['accountno']) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 50%;"><strong>Bank:</strong> ' . htmlspecialchars($accountInfo['bankname']) . '</td>
                        <td style="text-align: left; width: 50%;"><strong>Date Range:</strong> ' . htmlspecialchars($validfrom) . ' to ' . htmlspecialchars($validto) . '</td>
                    </tr>
                </table>
        
                <table class="tablec">
                    <thead>
                        <tr>
                            <th class="thc">Date</th>
                            <th class="thc">Cash IN</th>
                            <th class="thc">Cash OUT</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>
                <td class="tdc">' . htmlspecialchars($row['date']) . '</td>
                <td class="tdc">' . htmlspecialchars($row['cashin']) . '</td>
                <td class="tdc">' . htmlspecialchars($row['cashout']) . '</td>
            </tr>';
        }

        $html .= '</tbody>
                    <tfoot>
                        <tr>
                            <td class="tdc"><strong>Total:</strong></td>
                            <td class="tdc"><strong>' . number_format($totalCashIn, 2) . '</strong></td>
                            <td class="tdc"><strong>' . number_format($totalCashOut, 2) . '</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="tdc"><strong>Balance: ' . number_format($balance, 2) . '</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream(htmlspecialchars($accountInfo['accountno']) . ' Expenses Details.pdf', ['Attachment' => 0]);

    } else {
        echo "<div style=\"color: red; font-size:20px;\">Invalid Account ID provided.</div>";
        exit();
    }
} elseif ($searchType == '1') {
    
    $sql = "SELECT * FROM tbl_pettycash_expenses WHERE tbl_account_petty_cash_account = ? AND DATE(insertdatetime) BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $accountID, $validfrom, $validto);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    
    $sqlaccountinfo = "SELECT u.account, u.accountno, u.idtbl_account, ub.bankname 
FROM tbl_account AS u 
LEFT JOIN tbl_bank AS ub ON u.tbl_bank_idtbl_bank = ub.idtbl_bank";

    $accountInfoResult = $conn->query($sqlaccountinfo);
    $accountInfos = $accountInfoResult->fetch_all(MYSQLI_ASSOC);

    $allRows = [];
    $grandTotalCashIn = 0;
    $grandTotalCashOut = 0;

    foreach ($accountInfos as $accountInfo) {
    $accountID = $accountInfo['idtbl_account'];

    
    $sqlexpensesinfo = "SELECT amount AS expencesamount, narration, DATE(insertdatetime) AS expencesdate 
            FROM tbl_pettycash_expenses 
            WHERE tbl_account_petty_cash_account = '$accountID' 
            AND DATE(insertdatetime) BETWEEN '$validfrom' AND '$validto'
            ORDER BY DATE(insertdatetime) ASC";

    $expensesResult = $conn->query($sqlexpensesinfo);

    
    $sqlreimburseinfo = "SELECT amount AS reimburseamount, DATE(insertdatetime) AS reimbursedate 
            FROM tbl_pettycash_reimburse 
            WHERE tbl_account_petty_cash_account = '$accountID' 
            AND DATE(insertdatetime) BETWEEN '$validfrom' AND '$validto'
            ORDER BY DATE(insertdatetime) ASC";

    $reimburseResult = $conn->query($sqlreimburseinfo);

    
    $totalCashIn = 0;
    $totalCashOut = 0;

    $rows = [];

    
    while ($expRow = $expensesResult->fetch_assoc()) {
    $rows[] = [
    'date' => $expRow['expencesdate'],
    'cashin' => '-',
    'cashout' => $expRow['expencesamount'],
    ];
    $totalCashOut += $expRow['expencesamount'];
    }

   
    while ($reimburseRow = $reimburseResult->fetch_assoc()) {
    $rows[] = [
    'date' => $reimburseRow['reimbursedate'],
    'cashin' => $reimburseRow['reimburseamount'],
    'cashout' => '-',
    ];
    $totalCashIn += $reimburseRow['reimburseamount'];
    }

    
    usort($rows, function($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
    });

    $grandTotalCashIn += $totalCashIn;
    $grandTotalCashOut += $totalCashOut;

    
    $allRows[] = [
    'accountInfo' => $accountInfo,
    'rows' => $rows,
    'totalCashIn' => $totalCashIn,
    'totalCashOut' => $totalCashOut,
    ];
    }

    
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
    <div style="font-family: Arial, sans-serif; font-size: 12px; line-height: 1.2;">
        <h3 style="text-align: center; margin-bottom: 10px;"><strong>Account Statement All</strong></h3>
        <p style="margin-bottom: 10px;"><strong>Date Range: ' . htmlspecialchars($validfrom) . ' to ' . htmlspecialchars($validto) . '</strong></p>';

        $grandTotalBalance = 0;
        foreach ($allRows as $accountData) {
            $accountInfo = $accountData['accountInfo'];
            $rows = $accountData['rows'];
            $totalCashIn = $accountData['totalCashIn'];
            $totalCashOut = $accountData['totalCashOut'];
            $accountBalance = $totalCashIn - $totalCashOut;

            $grandTotalBalance += $accountBalance;

            $html .= '<h4 style="margin-bottom: 5px; font-size: 14px;"><strong>Account: ' . htmlspecialchars($accountInfo['account']) . ' (' . htmlspecialchars($accountInfo['accountno']) . ')</strong></h4>';
            $html .= '<h5 style="margin-bottom: 10px; font-size: 12px;"><strong>Bank: ' . htmlspecialchars($accountInfo['bankname']) . '</strong></h5>';

            $html .= '<table class="table table-striped table-bordered table-sm small" style="width: 100%; border: 1px solid black; border-collapse: collapse; font-size: 11px;">
            <thead>
            <tr>
                <th style="border: 1px solid black; padding: 3px; text-align: left;">Date</th>
                <th style="border: 1px solid black; padding: 3px; text-align: right;">Cash IN</th>
                <th style="border: 1px solid black; padding: 3px; text-align: right;">Cash OUT</th>
            </tr>
            </thead>
            <tbody>';

            foreach ($rows as $row) {
                $html .= '<tr>
                    <td style="border: 1px solid black; padding: 3px;">' . htmlspecialchars($row['date']) . '</td>
                    <td style="border: 1px solid black; text-align: right; padding: 3px;">' . htmlspecialchars($row['cashin']) . '</td>
                    <td style="border: 1px solid black; text-align: right; padding: 3px;">' . htmlspecialchars($row['cashout']) . '</td>
                </tr>';
            }

            $html .= '</tbody>
                <tfoot>
                    <tr>
                        <td style="border: 1px solid black; padding: 3px; text-align: center;"><strong>Total:</strong></td>
                        <td style="border: 1px solid black; padding: 3px; text-align: right;"><strong>' . number_format($totalCashIn, 2) . '</strong></td>
                        <td style="border: 1px solid black; padding: 3px; text-align: right;"><strong>' . number_format($totalCashOut, 2) . '</strong></td>
                    </tr>
                </tfoot>
            </table>';
        }

        $html .= '<h4 style="text-align: right; margin-top: 10px; font-size: 13px;"><strong>Grand Total Cash IN: ' . number_format($grandTotalCashIn, 2) . '</strong></h4>';
        $html .= '<h4 style="text-align: right; margin-top: 5px; font-size: 13px;"><strong>Grand Total Cash OUT: ' . number_format($grandTotalCashOut, 2) . '</strong></h4>';
        $html .= '<h4 style="text-align: right; margin-top: 5px; font-size: 13px;"><strong>Grand Total Balance: ' . number_format($grandTotalBalance, 2) . '</strong></h4>';
    $html .= '</div>
</body>
</html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('Report.pdf', ['Attachment' => 0]);

} else {
    echo "<div style=\"color: red; font-size:20px;\">Invalid Search Type.</div>";
    exit();
}
?>