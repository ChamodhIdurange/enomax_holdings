<?php
require_once('../connection/db.php');

$sql = "SELECT 
        fi.tbl_product_free_issue_id,
        p.product_code,
        p.product_name,
        fi.buy_quantity,
        fi.free_quantity,
        fi.start_date,
        fi.end_date,
        fi.status
        FROM tbl_product_free_issue fi
        INNER JOIN tbl_product p ON fi.product_id = p.idtbl_product
        ORDER BY fi.start_date DESC";

$result = $conn->query($sql);

$data = [];
$row_num = 1;

while ($row = $result->fetch_assoc()) {
    $row['row_num'] = $row_num++;
    $data[] = $row;
}

echo json_encode(["data" => $data]);
