<?php
require_once('../connection/db.php');

$fromdate = $_POST['fromdate'] ?? date("Y-m-01");
$todate   = $_POST['todate'] ?? date("Y-m-d");

$sql = "SELECT DISTINCT e.idtbl_employee, e.name
        FROM tbl_employee e
        INNER JOIN tbl_customer_order co 
            ON co.tbl_employee_idtbl_employee = e.idtbl_employee
           AND co.status = '1'
           AND co.date BETWEEN '$fromdate' AND '$todate'
        WHERE e.status = 1
        ORDER BY e.name ASC";

$res = $conn->query($sql);

$options = "";

if ($res && $res->num_rows > 0) {
    $count = 0;
    while ($row = $res->fetch_assoc()) {
        $selected = ($count < 4) ? 'selected' : '';
        $options .= '<option value="'.$row['idtbl_employee'].'" '.$selected.'>'.$row['name'].'</option>';
        $count++;
    }
} else {
    // No reps found
    $options .= '<option value="">No Sales Reps found for selected period</option>';
}

echo $options;
