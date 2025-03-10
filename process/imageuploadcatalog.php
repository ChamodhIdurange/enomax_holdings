<?php
require_once('../connection/db.php');

$id = $_POST['id']; 
$errors = [];

$deleteQuery = "DELETE FROM tbl_product_image WHERE idtbl_product_image = $id";
if ($conn->query($deleteQuery) === TRUE) {
    echo "File deleted successfully";
} else {
    $errors[] = "Error deleting record from database: " . $conn->error;
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}
?>
