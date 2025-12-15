<?php
require_once('../connection/db.php');

$validfrom = $_POST['validfrom'];
$validto   = $_POST['validto'];
$type      = $_POST['type']; 

switch ($type) {
    case "week":
        $selectPeriod = "DATE_FORMAT(DATE_SUB(u.date, INTERVAL WEEKDAY(u.date) DAY), '%b %d %Y')";
        $groupBy = "DATE_SUB(u.date, INTERVAL WEEKDAY(u.date) DAY)";
        break;

    case "month":
        $selectPeriod = "DATE_FORMAT(u.date, '%b %Y')";
        $groupBy = "YEAR(u.date), MONTH(u.date)";
        break;

    case "monthperiod":
        $selectPeriod = "CONCAT(MONTHNAME(u.date), ' ', YEAR(u.date))";
        $groupBy = "YEAR(u.date), MONTH(u.date)";
        break;

    default:
        $selectPeriod = "DATE(u.date)";
        $groupBy = "DATE(u.date)";
}


$sql = "SELECT 
            $selectPeriod AS period,
            SUM(u.total) AS total_sales
        FROM tbl_invoice u
        LEFT JOIN tbl_customer_order uf 
            ON u.tbl_customer_order_idtbl_customer_order = uf.idtbl_customer_order
        WHERE u.date BETWEEN '$validfrom' AND '$validto'
          AND u.status = 1
          AND uf.delivered = 1
        GROUP BY $groupBy
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
