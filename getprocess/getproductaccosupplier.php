<?php
session_start();
require_once('../connection/db.php');

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$supplierId = isset($_POST['supplierId']) ? $_POST['supplierId'] : '';

$data = array();

if ($supplierId) {
    // Get products with their stock batches for specific supplier
    if ($searchTerm) {
        $sql = "SELECT s.`idtbl_stock`, s.`batchqty`, s.`qty`, p.`idtbl_product`, p.`product_name`, p.`unitprice`
                FROM `tbl_stock` s
                INNER JOIN `tbl_product` p ON s.`tbl_product_idtbl_product` = p.`idtbl_product`
                WHERE p.`tbl_supplier_idtbl_supplier` = '$supplierId' 
                AND p.`status` = 1
                AND s.`qty` > 0
                AND p.`product_name` LIKE '%$searchTerm%'
                ORDER BY p.`product_name` ASC, s.`insertdatetime` ASC
                LIMIT 50";
    } else {
        $sql = "SELECT s.`idtbl_stock`, s.`batchqty`, s.`qty`, p.`idtbl_product`, p.`product_name`, p.`unitprice`
                FROM `tbl_stock` s
                INNER JOIN `tbl_product` p ON s.`tbl_product_idtbl_product` = p.`idtbl_product`
                WHERE p.`tbl_supplier_idtbl_supplier` = '$supplierId' 
                AND p.`status` = 1
                AND s.`qty` > 0
                ORDER BY p.`product_name` ASC, s.`insertdatetime` ASC
                LIMIT 50";
    }
} else {
    // Get all products with stock batches if no supplier selected
    if ($searchTerm) {
        $sql = "SELECT s.`idtbl_stock`, s.`batchqty`, s.`qty`, p.`idtbl_product`, p.`product_name`, p.`unitprice`
                FROM `tbl_stock` s
                INNER JOIN `tbl_product` p ON s.`tbl_product_idtbl_product` = p.`idtbl_product`
                WHERE p.`status` = 1
                AND s.`qty` > 0
                AND p.`product_name` LIKE '%$searchTerm%'
                ORDER BY p.`product_name` ASC, s.`insertdatetime` ASC
                LIMIT 50";
    } else {
        $sql = "SELECT s.`idtbl_stock`, s.`batchqty`, s.`qty`, p.`idtbl_product`, p.`product_name`, p.`unitprice`
                FROM `tbl_stock` s
                INNER JOIN `tbl_product` p ON s.`tbl_product_idtbl_product` = p.`idtbl_product`
                WHERE p.`status` = 1
                AND s.`qty` > 0
                ORDER BY p.`product_name` ASC, s.`insertdatetime` ASC
                LIMIT 50";
    }
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $batchInfo = $row['batchqty'] ? " [Batch: " . $row['batchqty'] . "]" : "";
        
        $data[] = array(
            "id" => $row['idtbl_product'],
            "text" => $row['product_name'] . $batchInfo . " (Stock: " . $row['qty'] . ", Unit Price: Rs. " . number_format($row['unitprice'], 2) . ")",
            "unitprice" => $row['unitprice'],
            "stock" => $row['qty'],
            "stockid" => $row['idtbl_stock'],  // Stock row ID
            "batchqty" => $row['batchqty']      // Batch number
        );
    }
}

echo json_encode($data);
$conn->close();
?>