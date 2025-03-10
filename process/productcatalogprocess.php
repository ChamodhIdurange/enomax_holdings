<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit();
}
require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$recordOption = $_POST['recordOption'];
$recordID = !empty($_POST['recordID']) ? $_POST['recordID'] : null;
$catalogcat = $_POST['catalogcat'];
$tableData = json_decode($_POST['tableData']);
$uom = 0;
$type = 0;
$updatedatetime = date('Y-m-d h:i:s');

if ($recordOption == 1) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $query = "INSERT INTO `tbl_catalog`(`uom`, `group_type`, `tbl_catalog_category_idtbl_catalog_category`, `tbl_user_idtbl_user`, `status`, `inserdatetime`) 
              VALUES ('$uom', '$type', '$catalogcat', '$userID', '1', '$updatedatetime')";

    if ($conn->query($query) === TRUE) {
        $catalogID = $conn->insert_id;
        $target_path = "../images/uploads/catalogitem/";
        $errors = [];

        if (!empty($_FILES["productimage"]["name"])) {
            $extension = ["jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF"];
            foreach ($_FILES["productimage"]["tmp_name"] as $key => $tmp_name) {
                $imageRandNum = rand(0, 100000000);
                $file_name = $_FILES["productimage"]["name"][$key];
                $file_tmp = $_FILES["productimage"]["tmp_name"][$key];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                if (in_array($ext, $extension)) {
                    $filename = basename($file_name, ".$ext");
                    $newFileName = md5($filename) . date('Y-m-d') . date('h-i-sa') . $imageRandNum . "." . $ext;
                    $newFilePath = $target_path . $newFileName;

                    if (move_uploaded_file($file_tmp, $newFilePath)) {
                        $productimagepath = substr($newFilePath, 3); // Trim "../" from the path
                        $imquery = "INSERT INTO tbl_product_image (imagepath, status, updatedatetime, tbl_userid, tbl_catalog_idtbl_catalog) 
                                    VALUES ('$productimagepath', '1', '$updatedatetime', '$userID', '$catalogID')";
                        $conn->query($imquery);
                    } else {
                        $errors[] = "Failed to upload $file_name";
                    }
                } else {
                    $errors[] = "$file_name is not a valid image file.";
                }
            }
        }

        foreach ($tableData as $rowtabledata) {
            $product = $rowtabledata->col_5;
            $tbl_catalog_details = "INSERT INTO tbl_catalog_details (product_name, status, updatedatetime, tbl_userid, tbl_catalog_idtbl_catalog) 
                                    VALUES ('$product', '1', '$updatedatetime', '$userID', '$catalogID')";
            $conn->query($tbl_catalog_details);
        }

        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Record Added Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'success';
        echo json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';
        echo json_encode($actionObj);
    }
} else {
    $query = "UPDATE `tbl_catalog` SET `uom` = '$uom', `group_type` = '$type', `tbl_catalog_category_idtbl_catalog_category` = '$catalogcat', `tbl_user_idtbl_user` = '$userID', `inserdatetime` =  '$updatedatetime' WHERE `idtbl_catalog` = '$recordID'"; 
    
    if ($conn->query($query) === TRUE) {
        $catalogID = $recordID;
        $target_path = "../images/uploads/catalogitem/";
        $errors = [];

        if (!empty($_FILES["productimage"]["name"])) {
            $extension = ["jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF"];
            foreach ($_FILES["productimage"]["tmp_name"] as $key => $tmp_name) {
                $imageRandNum = rand(0, 100000000);
                $file_name = $_FILES["productimage"]["name"][$key];
                $file_tmp = $_FILES["productimage"]["tmp_name"][$key];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                if (in_array($ext, $extension)) {
                    $filename = basename($file_name, ".$ext");
                    $newFileName = md5($filename) . date('Y-m-d') . date('h-i-sa') . $imageRandNum . "." . $ext;
                    $newFilePath = $target_path . $newFileName;

                    if (move_uploaded_file($file_tmp, $newFilePath)) {
                        $productimagepath = substr($newFilePath, 3); // Trim "../" from the path
                        $imquery = "INSERT INTO tbl_product_image (imagepath, status, updatedatetime, tbl_userid, tbl_catalog_idtbl_catalog) 
                          VALUES ('$productimagepath', '1', '$updatedatetime', '$userID', '$catalogID')";
                        $conn->query($imquery);
                    } else {
                        $errors[] = "Failed to upload $file_name";
                    }
                } else {
                    $errors[] = "$file_name is not a valid image file.";
                }
            }
        }
        $deleterecode = " DELETE FROM `tbl_catalog_details` WHERE `tbl_catalog_idtbl_catalog` = '$recordID'";
        $conn->query($deleterecode);

        foreach ($tableData as $rowtabledata) {
            $product = $rowtabledata->col_5;
            $tbl_catalog_details = "INSERT INTO tbl_catalog_details (product_name, status, updatedatetime, tbl_userid, tbl_catalog_idtbl_catalog) 
                          VALUES ('$product', '1', '$updatedatetime', '$userID', '$catalogID')";
            $conn->query($tbl_catalog_details);
        }

        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-check-circle';
        $actionObj->title = '';
        $actionObj->message = 'Record Update Successfully';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'primary';
        echo json_encode($actionObj);
    } else {
        $actionObj = new stdClass();
        $actionObj->icon = 'fas fa-exclamation-triangle';
        $actionObj->title = '';
        $actionObj->message = 'Record Error';
        $actionObj->url = '';
        $actionObj->target = '_blank';
        $actionObj->type = 'danger';
        echo json_encode($actionObj);
    }
}
