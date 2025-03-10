<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
}
require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$recordOption = $_POST['recordOption'];
if (!empty($_POST['recordID'])) {
    $recordID = $_POST['recordID'];
}
$category = addslashes($_POST['category']);
$sizecategory = $_POST['sizecategory'];
$sequence = $_POST['sequence'];

$updatedatetime = date('Y-m-d h:i:s');

$querycheck = "SELECT * FROM `tbl_catalog_category` WHERE `sequence` = '$sequence' AND `status` IN (1,2)";
$result = $conn->query($querycheck);

if ($result->num_rows > 0) {
        $queryupdateSeq = "UPDATE `tbl_catalog_category` SET `sequence`=(`sequence`+1) WHERE `sequence` >= $sequence AND `status` IN (1,2)";
        $conn->query($queryupdateSeq);
        echo 'asd';
}else{
    echo 'dd';

}

if ($recordOption == 1) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $insert = "INSERT INTO `tbl_catalog_category`(`category`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `sequence`, `tbl_size_categories_idtbl_size_categories`) VALUES ('$category','1','$updatedatetime','$userID', '$sequence', '$sizecategory')";
    if ($conn->query($insert) == true) {
        header("Location:../catalogcategoies.php?action=4");
    } else {
        header("Location:../catalogcategoies.php?action=5");
    }
} else {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $update = "UPDATE `tbl_catalog_category` SET `category`='$category',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID', `sequence` = '$sequence', `tbl_size_categories_idtbl_size_categories` = '$sizecategory'  WHERE `idtbl_catalog_category`='$recordID'";
    if ($conn->query($update) == true) {
        header("Location:../catalogcategoies.php?action=6");
    } else {
        header("Location:../catalogcategoies.php?action=5");
    }
}
