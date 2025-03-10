<?php 
require_once('../connection/db.php');


$validfrom = $_POST['validfrom'];
$validto = $_POST['validto'];
$accountID = $_POST['selectedAccount'];
$searchType = $_POST['searchType'];

if ($searchType == '2') { 
    if ($accountID > 0) {
      
    $sqlaccountinfo = "SELECT `u`.`account`, `u`.`accountno`, `u`.`idtbl_account`, `ub`.`bankname` 
    FROM `tbl_account` AS `u` 
    LEFT JOIN `tbl_bank` AS `ub` ON `u`.`tbl_bank_idtbl_bank` = `ub`.`idtbl_bank` 
    WHERE `u`.`idtbl_account` = '$accountID'";

        $accountInfoResult = $conn->query($sqlaccountinfo);
        $accountInfo = $accountInfoResult->fetch_assoc();

        if (!$accountInfo) {
        echo "<div style=\"color: red; font-size:20px;\">No Account Information Found</div>";
        return;
        }

       
        $sqlexpensesinfo = "SELECT `amount` AS `expencesamount`, `narration`, DATE(`insertdatetime`) AS `expencesdate` 
                FROM `tbl_pettycash_expenses` 
                WHERE `tbl_account_petty_cash_account` = '$accountID' 
                AND DATE(`insertdatetime`) BETWEEN '$validfrom' AND '$validto'
                ORDER BY DATE(`insertdatetime`) ASC";

        $expensesResult = $conn->query($sqlexpensesinfo);

        
        $sqlreimburseinfo = "SELECT `amount` AS `reimburseamount`, DATE(`insertdatetime`) AS `reimbursedate` 
                FROM `tbl_pettycash_reimburse` 
                WHERE `tbl_account_petty_cash_account` = '$accountID' 
                AND DATE(`insertdatetime`) BETWEEN '$validfrom' AND '$validto'
                ORDER BY DATE(`insertdatetime`) ASC";

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

        
        $sqlbalance = "SELECT `balance` FROM `tbl_pettycash_account_balance` WHERE `tbl_account_petty_cash_account`='$accountID'";
        $balanceResult = $conn->query($sqlbalance);
        $balance = $balanceResult->fetch_assoc()['balance'] ?? 0;

        
        $html = '<div style="font-family: Arial, sans-serif;">
                    <h3 style="text-align: center;">Account Statement</h3>
                        <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
                            <tr>
                                <th style="text-align: left; width: 10%;">Account:</th>
                                <td style="text-align: left;">' . htmlspecialchars($accountInfo['account']) . '</td>
                                <th style="text-align: left; width: 10%;">Account No:</th>
                                <td style="text-align: left;">' . htmlspecialchars($accountInfo['accountno']) . '</td>
                            </tr>
                            <tr>
                                <th style="text-align: left;">Bank:</th>
                                <td style="text-align: left;">' . htmlspecialchars($accountInfo['bankname']) . '</td>
                                <th style="text-align: left;">Date Range:</th>
                                <td style="text-align: left;">' . htmlspecialchars($validfrom) . ' to ' . htmlspecialchars($validto) . '</td>
                            </tr>
                        </table>';

        $html .= '<table class="table table-striped table-bordered table-sm small" style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <thead>
                <tr>
                <th style="border: 1px solid black; padding: 5px;">Date</th>
                <th style="border: 1px solid black; padding: 5px;">Cash IN</th>
                <th style="border: 1px solid black; padding: 5px;">Cash OUT</th>
                </tr>
            </thead>
        <tbody>';

        foreach ($rows as $row) {
        $html .= '<tr>
                        <td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($row['date']) . '</td>
                        <td style="border: 1px solid black; text-align: center; padding: 5px;">' . htmlspecialchars($row['cashin']) . '</td>
                        <td style="border: 1px solid black; text-align: center; padding: 5px;">' . htmlspecialchars($row['cashout']) . '</td>
                    </tr>';
        }

        $html .= '</tbody>
        <tfoot>
            <tr>
                <td style="border: 1px solid black; padding: 5px; text-align: center;"><strong>Total:</strong></td>
                <td style="border: 1px solid black; padding: 5px; text-align: center;"><strong>' . number_format($totalCashIn, 2) . '</strong></td>
                <td style="border: 1px solid black; padding: 5px; text-align: center;"><strong>' . number_format($totalCashOut, 2) . '</strong></td>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid black; padding: 5px; text-align: center;"><strong>Balance: ' . number_format($balance, 2) . '</strong></td>
            </tr>
        </tfoot>
        </table>
        </div>';

        echo $html;
    }
}
elseif ($searchType == '1'){ 
        
    $sqlaccountinfo = "SELECT `u`.`account`, `u`.`accountno`, `u`.`idtbl_account`, `ub`.`bankname` 
    FROM `tbl_account` AS `u` 
    LEFT JOIN `tbl_bank` AS `ub` ON `u`.`tbl_bank_idtbl_bank` = `ub`.`idtbl_bank`";

        $accountInfoResult = $conn->query($sqlaccountinfo);
        $accountInfos = $accountInfoResult->fetch_all(MYSQLI_ASSOC);

        $allRows = [];
        $grandTotalCashIn = 0;
        $grandTotalCashOut = 0;

        foreach ($accountInfos as $accountInfo) {
        $accountID = $accountInfo['idtbl_account'];

        
        $sqlexpensesinfo = "SELECT `amount` AS `expencesamount`, `narration`, DATE(`insertdatetime`) AS `expencesdate` 
                FROM `tbl_pettycash_expenses` 
                WHERE `tbl_account_petty_cash_account` = '$accountID' 
                AND DATE(`insertdatetime`) BETWEEN '$validfrom' AND '$validto'
                ORDER BY DATE(`insertdatetime`) ASC";

        $expensesResult = $conn->query($sqlexpensesinfo);

        
        $sqlreimburseinfo = "SELECT `amount` AS `reimburseamount`, DATE(`insertdatetime`) AS `reimbursedate` 
                FROM `tbl_pettycash_reimburse` 
                WHERE `tbl_account_petty_cash_account` = '$accountID' 
                AND DATE(`insertdatetime`) BETWEEN '$validfrom' AND '$validto'
                ORDER BY DATE(`insertdatetime`) ASC";

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

       
        $html = '<div style="font-family: Arial, sans-serif; font-size: 12px; line-height: 1.2;">
        <h3 style="text-align: center; margin-bottom: 10px;"><strong></strong>Account Statement All</strong></h3>
        <p style="margin-bottom: 10px;"><strong></strong>Date Range: ' . htmlspecialchars($validfrom) . ' to ' . htmlspecialchars($validto) . '</strong></p>';
        $grandTotalBalance = 0;
        foreach ($allRows as $accountData) {
            $accountInfo = $accountData['accountInfo'];
            $rows = $accountData['rows'];
            $totalCashIn = $accountData['totalCashIn'];
            $totalCashOut = $accountData['totalCashOut'];
            $accountBalance = $totalCashIn - $totalCashOut;

            $grandTotalBalance += $accountBalance;

            $html .= '<h4 style="margin-bottom: 5px; font-size: 14px;"><strong></strong>Account: ' . htmlspecialchars($accountInfo['account']) . ' (' . htmlspecialchars($accountInfo['accountno']) . ')</strong></h4>';
            $html .= '<h5 style="margin-bottom: 10px; font-size: 12px;"><strong></strong>Bank: ' . htmlspecialchars($accountInfo['bankname']) . '</strong></h5>';

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
        $html .= '</div>';

        echo $html;

}
?>