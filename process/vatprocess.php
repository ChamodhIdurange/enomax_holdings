<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location:../index.php");
    exit();
}

require_once('../connection/db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_POST['vat']) || $_POST['vat'] === '') {
    header("Location:../vatinfo.php?action=5");
    exit();
}

$vat = floatval($_POST['vat']);
$currentDateTime = date('Y-m-d H:i:s');

$check = "SELECT idtbl_vat FROM tbl_vat LIMIT 1";
$checkResult = $conn->query($check);

if (!$checkResult) {
    die("Query Error: " . $conn->error);
}

if ($checkResult->num_rows > 0) {
    $row = $checkResult->fetch_assoc();
    $id = $row['idtbl_vat'];
    
    $stmt = $conn->prepare("UPDATE tbl_vat SET vat_rate=?, status=1, insertdatetime=? WHERE idtbl_vat=?");
    
    if ($stmt === false) {
        die("Prepare Error (UPDATE): " . $conn->error);
    }
    
    $stmt->bind_param("dsi", $vat, $currentDateTime, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location:../vatinfo.php?action=6");
        exit();
    } else {
        die("Execute Error: " . $stmt->error);
    }
} else {
    $stmt = $conn->prepare("INSERT INTO tbl_vat (vat_rate, status, insertdatetime) VALUES (?, 1, ?)");
    
    if ($stmt === false) {
        die("Prepare Error (INSERT): " . $conn->error);
    }
    
    $stmt->bind_param("ds", $vat, $currentDateTime);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location:../vatinfo.php?action=4");
        exit();
    } else {
        die("Execute Error: " . $stmt->error);
    }
}
?>