<?php
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate   = $_POST['todate'];

// $sql = "SELECT  
//             u.date, 
//             SUM(u.total) AS total_sale,
//             SUM(u.nettotal) AS nettotal,
//             SUM((d.saleprice - d.unitprice) * d.qty) AS total_profit,
//             SUM(d.unitprice * d.qty) AS total_cost,
//             SUM(d.discount) AS total_discount
//         FROM tbl_invoice AS u  
//         LEFT JOIN tbl_invoice_detail AS d 
//             ON d.tbl_invoice_idtbl_invoice = u.idtbl_invoice 
//         LEFT JOIN tbl_customer_order AS uf 
//             ON u.tbl_customer_order_idtbl_customer_order = uf.idtbl_customer_order
//         WHERE u.status IN (1, 2) 
//             AND d.status = '1'
//             AND uf.delivered = '1'
//             AND u.date BETWEEN '$fromdate' AND '$todate'
//         GROUP BY u.date
//         ORDER BY u.date";

$sql = "SELECT 
            u.date,
            SUM(u.total) AS total_sale,
            SUM(u.nettotal) AS nettotal,
            SUM(d_tot.total_profit) AS total_profit,
            SUM(d_tot.total_cost) AS total_cost,
            SUM(d_tot.total_discount) AS total_discount
        FROM tbl_invoice AS u
        LEFT JOIN (
            SELECT tbl_invoice_idtbl_invoice,
                SUM((saleprice - unitprice) * qty) AS total_profit,
                SUM(unitprice * qty) AS total_cost,
                SUM(discount) AS total_discount
            FROM tbl_invoice_detail
            WHERE status='1'
            GROUP BY tbl_invoice_idtbl_invoice
        ) AS d_tot ON d_tot.tbl_invoice_idtbl_invoice = u.idtbl_invoice
        LEFT JOIN tbl_customer_order AS uf 
            ON u.tbl_customer_order_idtbl_customer_order = uf.idtbl_customer_order
        WHERE u.status IN (1,2)
        AND uf.delivered='1'
        AND u.date BETWEEN '$validfrom' AND '$validto'
        GROUP BY u.date
        ORDER BY u.date";


$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "date" => $row['date'],
        "sale" => (float)$row['total_sale'],
        "net_sale" => (float)$row['nettotal'],
        "cost" => (float)$row['total_cost'],
        "profit" => (float)$row['total_profit'],
        "profit_with_discount" => (float)$row['total_profit'] - (float)$row['total_discount']
    ];

}

header('Content-Type: application/json');
echo json_encode($data);
