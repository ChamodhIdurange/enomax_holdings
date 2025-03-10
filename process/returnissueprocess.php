<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("");
}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');

$record = $_POST['id'];


$sql = "UPDATE `tbl_return` SET `credit_note`='1',`updatedatetime`='$updatedatetime' WHERE `idtbl_return`='$record'";
if ($conn->query($sql) == true) {
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-check-circle';
    $actionObj->title = '';
    $actionObj->message = 'Add to Credit Note';
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'success';
    echo $actionJSON=json_encode($actionObj);
} else {
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}
