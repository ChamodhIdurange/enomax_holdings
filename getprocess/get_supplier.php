<?php
require_once('../connection/db.php');

if (isset($_POST['common_name'])) {
    $commonName = $_POST['common_name'];

    // Query to get supplier details based on common name
    $query = "SELECT s.idtbl_supplier, s.suppliername 
              FROM tbl_product p 
              JOIN tbl_supplier s ON p.tbl_supplier_idtbl_supplier = s.idtbl_supplier 
              WHERE p.common_name = '$commonName' 
              LIMIT 1";
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $supplier = $result->fetch_assoc();
        echo json_encode($supplier);
    } else {
        echo json_encode(['idtbl_supplier' => '', 'suppliername' => '']);
    }
}

$conn->close();
?>
