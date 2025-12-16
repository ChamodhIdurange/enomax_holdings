<?php
session_start();
require_once('../connection/db.php');

// Get today's date
$today = date("Y-m-d");

// Initialize response array
$response = [
    "sales_today"    => 0,
    "cash_today"     => 0,
    "purchases_month"=> 0,
    "profit_month"   => 0,
    "profit_item"    => 0 // <-- New KPI for Profit Analysis Report (Item)
];

/* ==========================
   1️⃣ Total Sales (Today)
   ========================== */
$sql_sales = "
    SELECT COALESCE(SUM(nettotal), 0) AS total_sales
    FROM tbl_invoice
    WHERE DATE(date) = CURDATE()
      AND status != 0
";
$result = $conn->query($sql_sales);
if ($result && $row = $result->fetch_assoc()) {
    $response["sales_today"] = (float)$row["total_sales"];
}

/* ==========================
   2️⃣ Cash & Cheques (Today)
   ========================== */
$sql_cash = "
    SELECT COALESCE(SUM(ipd.amount), 0) AS total_payment
    FROM tbl_invoice_payment_detail AS ipd
    INNER JOIN tbl_invoice_payment AS ip
        ON ip.idtbl_invoice_payment = ipd.tbl_invoice_payment_idtbl_invoice_payment
    WHERE ipd.status = 1
      AND ipd.method IN (1, 2) 
      AND DATE(ip.date) = CURDATE() //
";
$result = $conn->query($sql_cash);
if ($result && $row = $result->fetch_assoc()) {
    $response["cash_today"] = (float)$row["total_payment"];
}

/* ==========================
   3️⃣ Total Purchases (This Month)
   ========================== */
$sql_purchases = "
    SELECT SUM(nettotal) AS total_purchases
    FROM tbl_customer_order
    WHERE status = 1
      AND MONTH(date) = MONTH(CURDATE())
      AND YEAR(date) = YEAR(CURDATE())
";
$result = $conn->query($sql_purchases);
if ($result && $row = $result->fetch_assoc()) {
    $response["purchases_month"] = (float)$row["total_purchases"];
}

/* ==========================
   4️⃣ Total Profit (This Month)
   ========================== */
$sql_profit = "
    SELECT  
        SUM((invd.saleprice - invd.unitprice) * invd.qty) AS total_profit
    FROM tbl_customer_order AS u
    LEFT JOIN tbl_customer_order_detail AS d 
        ON d.tbl_customer_order_idtbl_customer_order = u.idtbl_customer_order
    LEFT JOIN tbl_invoice AS i 
        ON u.idtbl_customer_order = i.tbl_customer_order_idtbl_customer_order
    LEFT JOIN tbl_invoice_detail AS invd 
        ON invd.tbl_invoice_idtbl_invoice = i.idtbl_invoice
    WHERE u.status IN (1, 2)
      AND u.delivered = '1'
      AND d.status = '1'
      AND DATE(u.date) = CURDATE()
";
$result = $conn->query($sql_profit);
if ($result && $row = $result->fetch_assoc()) {
    $response["profit_month"] = (float)$row["total_profit"];
}

/* ==========================
   5️⃣ Profit Analysis Report (Item) - New KPI
   ========================== */
$sql_profit_item = "
    SELECT  
        SUM((invd.saleprice - invd.unitprice) * invd.qty) AS total_profit_item
    FROM tbl_customer_order AS u
    LEFT JOIN tbl_customer_order_detail AS d 
        ON d.tbl_customer_order_idtbl_customer_order = u.idtbl_customer_order
    LEFT JOIN tbl_invoice AS i 
        ON u.idtbl_customer_order = i.tbl_customer_order_idtbl_customer_order
    LEFT JOIN tbl_invoice_detail AS invd 
        ON invd.tbl_invoice_idtbl_invoice = i.idtbl_invoice
    WHERE u.status IN (1, 2)
      AND u.delivered = '1'
      AND d.status = '1'
";
$result = $conn->query($sql_profit_item);
if ($result && $row = $result->fetch_assoc()) {
    $response["profit_item"] = (float)$row["total_profit_item"];
}

/* ==========================
   Return as JSON
   ========================== */
header('Content-Type: application/json');
echo json_encode($response);
?>
