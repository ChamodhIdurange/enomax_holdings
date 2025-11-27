<?php
require_once('../connection/db.php');

$validfrom = $_POST['validfrom'];
$validto   = $_POST['validto'];
$type      = $_POST['type']; 

switch ($type) {
    case "week":
        $selectPeriod = "DATE_FORMAT(DATE_SUB(u.date, INTERVAL WEEKDAY(u.date) DAY), '%b %d') AS period";
        break;
    case "month":
        $selectPeriod = "DATE_FORMAT(u.date, '%b %Y') AS period"; 
        break;
    case "monthperiod":
        $selectPeriod = "CONCAT(MONTHNAME(u.date), ' ', YEAR(u.date)) AS period";
        break;
    default:
        $selectPeriod = "DATE(u.date) AS period";
}


$sql = "SELECT 
            $selectPeriod,
            SUM(u.total) AS total_sales
        FROM tbl_invoice u
        LEFT JOIN tbl_customer_order uf 
            ON u.tbl_customer_order_idtbl_customer_order = uf.idtbl_customer_order
        LEFT JOIN tbl_invoice_detail ud 
            ON u.idtbl_invoice = ud.tbl_invoice_idtbl_invoice
        WHERE u.date BETWEEN '$validfrom' AND '$validto'
          AND u.status = 1
          AND ud.status = 1
          AND uf.delivered = 1
        GROUP BY period
        ORDER BY MIN(u.date)";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "period" => $row['period'],
        "total_sales" => (float)$row['total_sales']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
