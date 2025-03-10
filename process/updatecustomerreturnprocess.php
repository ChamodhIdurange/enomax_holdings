<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("");
}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');

$returndate = $_POST['returndate'];
$returnId = $_POST['returnId'];


$updateInvoice = "UPDATE `tbl_return` SET `returndate`='$returndate' WHERE `idtbl_return`='$returnId'";
if ($conn->query($updateInvoice) == true) {
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
