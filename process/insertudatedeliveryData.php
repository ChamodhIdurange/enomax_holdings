<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("");
}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');

$deliverDate = $_POST['deliverDate'];
$deliverVehicle = $_POST['deliverVehicle'];
$porderId = $_POST['porderId'];
$vehicleRemarks = $_POST['vehicleRemarks'];


$checkSql = "SELECT COUNT(*) as count FROM `tbl_customer_order_delivery_data` WHERE `tbl_customer_order_idtbl_customer_order` = '$porderId'";
$result = mysqli_query($conn, $checkSql);
$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    $sql = "UPDATE `tbl_customer_order_delivery_data` 
            SET `deliverDate` = '$deliverDate', 
                `deliverRemarks` = '$vehicleRemarks', 
                `tbl_vehicle_idtbl_vehicle` = '$deliverVehicle' 
            WHERE `tbl_customer_order_idtbl_customer_order` = '$porderId'";
} else {
    $sql = "INSERT INTO `tbl_customer_order_delivery_data` 
            (`deliverDate`, `deliverRemarks`, `status`, `tbl_vehicle_idtbl_vehicle`, `tbl_customer_order_idtbl_customer_order`) 
            VALUES ('$deliverDate', '$vehicleRemarks', '1', '$deliverVehicle', '$porderId')";
}

if ($conn->query($sql) == true) {
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-check-circle';
    $actionObj->title = '';
    $actionObj->message = 'Record Updated Successfully';
    $actionObj->type = 'success';
    $actionJSON=json_encode($actionObj);

    $obj=new stdClass();
    $obj->status=1;          
    $obj->action=$actionJSON;  

    echo json_encode($obj); 

} else {
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->type='danger';

    $obj=new stdClass();
    $obj->status=0;          
    $obj->action=$actionJSON;  
    
    echo json_encode($obj); 
}
