<?php 
session_start();
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'];
$todate   = $_POST['todate'];
$replist  = $_POST['replist'];
$replist  = implode(", ", $replist);

$sql = "SELECT 
            e.name,
            COALESCE(SUM(co.nettotal), 0) AS sales,
            COALESCE(SUM(CASE WHEN co.delivered = '1' THEN co.nettotal ELSE 0 END), 0) AS approved_sales,
            COALESCE((SELECT SUM(r.total) 
                     FROM tbl_return r 
                     WHERE r.status = '1' 
                       AND r.acceptance_status = '0' 
                       AND r.returndate BETWEEN '$fromdate' AND '$todate' 
                       AND r.tbl_employee_idtbl_employee = e.idtbl_employee), 0) AS returns,
            COALESCE((SELECT SUM(r.total) 
                     FROM tbl_return r 
                     WHERE r.status = '1' 
                       AND r.acceptance_status = '1' 
                       AND r.returndate BETWEEN '$fromdate' AND '$todate' 
                       AND r.tbl_employee_idtbl_employee = e.idtbl_employee), 0) AS approved_returns
        FROM tbl_customer_order co
        LEFT JOIN tbl_employee e ON e.idtbl_employee = co.tbl_employee_idtbl_employee
        WHERE co.status = '1'  
          AND co.date BETWEEN '$fromdate' AND '$todate'
          AND co.tbl_employee_idtbl_employee IN ($replist)
        GROUP BY co.tbl_employee_idtbl_employee";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $net_sale = $row['approved_sales'] - $row['approved_returns'];
    $data[] = [
        "name"             => $row['name'],
        "sales"            => (float)$row['sales'],
        "approved_sales"   => (float)$row['approved_sales'],
        "returns"          => (float)$row['returns'],
        "approved_returns" => (float)$row['approved_returns'],
        "net_sale"         => (float)$net_sale
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
