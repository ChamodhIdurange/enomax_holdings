<?php
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate   = $_POST['todate'];

$sql = "SELECT  
            u.date, 
            u.invoiceno, 
            SUM((d.saleprice - d.unitprice) * d.qty) AS total_profit,
            SUM((d.unitprice) * d.qty) AS total_cost,
            u.nettotal,
            d.discount,
            u.total
        FROM tbl_invoice AS u  
        LEFT JOIN tbl_invoice_detail AS d 
            ON d.tbl_invoice_idtbl_invoice = u.idtbl_invoice 
        LEFT JOIN tbl_customer_order AS uf 
            ON u.tbl_customer_order_idtbl_customer_order = uf.idtbl_customer_order
        LEFT JOIN tbl_product AS p 
            ON p.idtbl_product = d.tbl_product_idtbl_product 
        WHERE u.status IN (1, 2) 
            AND d.status = '1'
            AND uf.delivered = '1'
            AND u.date BETWEEN '$fromdate' AND '$todate'
        GROUP BY u.idtbl_invoice";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "date" => $row['date'],
        "invoice" => $row['invoiceno'],
        "sale" => (float)$row['total'],
        "net_sale" => (float)$row['nettotal'],
        "cost" => (float)$row['total_cost'],
        "profit" => (float)$row['total_profit'],
        "profit_with_discount" => (float)$row['total_profit'] - (float)$row['discount']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
